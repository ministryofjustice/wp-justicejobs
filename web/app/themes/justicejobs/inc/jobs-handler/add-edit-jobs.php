<?php
// bootstrap WP
require_once(ABSPATH . "wp-load.php");

if (!function_exists('import_jobs_from_xml')) {

    function import_jobs_from_xml()
    {
        // update available?
        $job_data_updated = get_option('jobs_request_has_updated', true);
        if (!$job_data_updated) {
            return false;
        }

        // get admin email for simple notifications
        $to = get_option('admin_email');

        // collect significant data
        $errors = [];
        $counts = [
            'total-jobs' => 0,
            'inserted' => 0,
            'updated' => 0,
            'unchanged' => 0,
            'errored' => 0,
            'deleted' => 0
        ];

        // get the uploads directory path
        $upload_dir = wp_get_upload_dir();
        $file = $upload_dir['basedir'] . "/job-feed/jobs.xml";

        $xml = simplexml_load_file($file);

        if (!$xml) {
            // inform admin
            jj_simple_mail($to, [
                "[Justice Jobs] Security: error detected in jobs XML file",
                "Please check jobs.xml for errors. The job feed load process failed in " . __FUNCTION__
            ]);

            return false;
        }

        require_once(ABSPATH . 'wp-admin/includes/post.php');

        $total_jobs = count($xml->entry);
        $active_jobs = [];

        $counts['total-jobs'] = $total_jobs;

        for ($x = 0; $x < $total_jobs; $x++) {
            $job_content = $xml->entry[$x]->content;
            $total_spans = count($job_content->div->span);
            $job_title = $xml->entry[$x]->title;
            $job_link = (string)$xml->entry[$x]->id;

            if ($total_spans > 0) {
                $job_id = (string)$job_content->div->span[0];

                $job_content_hash = md5($job_content->div->asXML());

                $job_title = str_replace($job_id . " - ", "", $job_title);

                $job_title = $job_title . " - " . $job_id;

                $args = array(
                    'post_type' => 'job',
                    'numberposts' => -1,
                    'meta_query' => array(
                        array(
                            'key' => 'job_id',
                            'value' => $job_id,
                            'compare' => '=',
                        )
                    )
                );
                $job_check = get_posts($args);

                if (count($job_check) === 0) {
                    // Creates job post
                    $job_post = array(
                        'post_title' => $job_title,
                        'post_content' => ' ',
                        'post_status' => 'publish',
                        'post_type' => 'job'
                    );

                    // Insert the post into the database
                    $post_id = wp_insert_post($job_post);

                    if ($post_id) {
                        $counts['inserted']++;
                    }

                    $active_jobs[] = $post_id;

                    set_job_details($job_content, $total_spans, $post_id);

                    // Save Application Link
                    update_field('application_link', $job_link, $post_id);
                    update_field('job_id', $job_id, $post_id);
                    update_field('job_content_hash', $job_content_hash, $post_id);
                } else {
                    $post_id = $job_check[0]->ID;

                    $current_hash = get_field('job_content_hash', $post_id);

                    $active_jobs[] = $post_id;

                    if ($job_content_hash != $current_hash) {
                        set_job_details($job_content, $total_spans, $post_id);

                        update_field('application_link', $job_link, $post_id);
                        update_field('job_content_hash', $job_content_hash, $post_id);

                        $counts['updated']++;
                    } else {
                        $counts['unchanged']++;
                    }
                }

            } else {
                $counts['errored']++;
                array_push($errors, [$job_title, $job_link]);
            }

        }

        if (count($active_jobs) > 0) {
            $all_posts = get_posts(array('post_type' => 'job', 'post__not_in' => $active_jobs, 'numberposts' => -1));
            foreach ($all_posts as $each_post) {
                echo "Job Deleted - " . $each_post->post_title;
                if (wp_delete_post($each_post->ID, true)) {
                    $counts['deleted']++;
                }
            }
        }

        // update request flag.
        update_option('jobs_request_has_updated', false);

        // cast integers to strings
        $counts = array_map(function ($n) {
            return (string)$n;
        }, $counts);

        // send simple notification with counts
        $message = "Please find the latest job import stats\n\n" .
            "Total Jobs: " . $counts['total-jobs'] . "\n" .
            "Inserted: " . $counts['inserted'] . "\n" .
            "Updated: " . $counts['updated'] . "\n" .
            "Unchanged: " . $counts['unchanged'] . "\n" .
            "Errored: " . $counts['errored'] . "\n" .
            "Deleted: " . $counts['deleted'] . "\n\n";

        if (!empty($errors)) {
            $message .= "Errors:\n";
            foreach ($errors as $error) {
                $error_title = $error[0] ?? 'No title saved';
                $error_link = $error[1] ?? 'No link saved';
                $message .= $error_title . " | " . $error_link . "\n";
            }
        }

        jj_simple_mail($to, [
            '[Justice Jobs] Latest job import results',
            $message
        ]);

        return true;
    }
}

function set_job_details($job_content, $totalspans, $post_id)
{

    $working_patterns = array();
    $job_locations = array();
    $job_city = '';

    for ($y = 0; $y < $totalspans; $y++) {
        // Save Role Type Info
        if ($job_content->div->span[$y]->attributes()->itemprop[0] == "Role Type") {
            $role_type = (string)$job_content->div->span[$y];

            wp_set_object_terms($post_id, $role_type, 'role_type');

        }

        // Save Salary Info
        if ($job_content->div->span[$y]->attributes()->itemprop[0] == "Salary Range") {
            $salary = (string)$job_content->div->span[$y];
            wp_set_object_terms($post_id, $salary, 'salary_range');

        }

        // Save Salary Min and Max
        if ($job_content->div->span[$y]->attributes()->itemprop[0] == "Salary Minimum") {
            $salary = (string)$job_content->div->span[$y];

            $salary_range_array = explode('-', $salary);

            $salary_min = '';
            $salary_max = '';

            if (count($salary_range_array) > 1) {
                $salary_min = preg_replace("/[^0-9]/", "", $salary_range_array[0]);
                $salary_max = preg_replace("/[^0-9]/", "", $salary_range_array[1]);
            } else {
                $salary_min = preg_replace("/[^0-9]/", "", $salary_range_array[0]);
            }

            if (is_numeric($salary_min)) {
                update_field('salary_min', $salary_min, $post_id);
            }
            if (is_numeric($salary_max)) {
                update_field('salary_max', $salary_max, $post_id);
            }

        }

        // Save Working Pattern Info
        if ($job_content->div->span[$y]->attributes()->itemprop[0] == "Working Pattern") {
            array_push($working_patterns, (string)$job_content->div->span[$y]);
            wp_set_object_terms($post_id, $working_patterns, 'working_pattern');

        }

        // Save Job Locations Info
        if ($job_content->div->span[$y]->attributes()->itemprop[0] == "Building/Site") {
            array_push($job_locations, (string)$job_content->div->span[$y]);
            wp_set_object_terms($post_id, $job_locations, 'job_location');

        }

        // Save Location Info
        if ($job_content->div->span[$y]->attributes()->itemprop[0] == "City/Town") {
            if (strlen($job_city) > 0) {
                $job_city = 'Multiple Locations';
            } else {
                $job_city = (string)$job_content->div->span[$y];
            }
        }

        // Save Closing Date
        if ($job_content->div->span[$y]->attributes()->itemprop[0] == "Closing Date") {
            $closing_date = (string)$job_content->div->span[$y];
            if (strlen($closing_date > 0)) {
                $closing_date = strtotime($closing_date);
                if ($closing_date != false) {
                    update_field('closing_date', $closing_date, $post_id);
                }
            }
        }

        // Save Job Description Info
        if ($job_content->div->span[$y]->attributes()->itemprop[0] == "Job description Additional Information") {
            $job_info_string = $job_content->div->span[$y]->asXML();

            $job_info_string = replace_content_headers($job_info_string);

            $update_post = array(
                'ID' => $post_id,
                'post_content' => $job_info_string
            );

            wp_update_post($update_post);
        }

        // Save Additional Info
        if ($job_content->div->span[$y]->attributes()->itemprop[0] == "Additional Information") {
            $job_add_info_string = $job_content->div->span[$y]->asXML();

            $job_add_info_string = replace_content_headers($job_add_info_string);

            update_field('additional_information', $job_add_info_string, $post_id);

        }

    }

    if (strlen($job_city) > 0) {
        update_field('location', $job_city, $post_id);
    }

}

function replace_content_headers($input_lines)
{
    $input_lines = preg_replace('/>(\s)+</m', '><', $input_lines);//remove whitespace between tags
    $input_lines = str_replace("</p>", "</p>\n", $input_lines);
    $pattern_array = [
        '/\<p.*\>\<span\sstyle\=\"font-size.*?\"\>(\<strong\>(.*)\<\/strong\>)\<\/span\><\/p>/',
        '/\<p.*\>(\<strong\>\<span\sstyle\=\"font-size.*?\"\>(.*)\<\/span\><\/strong\>)\<\/p>/',
        '/\<h1.*\>(\<span\sstyle\=\"font-size.*?\"\>(.*)\<\/span\>)\<\/h1>/'
    ];

    foreach ($pattern_array as $pattern) {
        preg_match_all(
            $pattern,
            $input_lines,
            $output_array
        );
        if (!empty($output_array)) {
            $headings = [];
            foreach ($output_array[0] as $key => $original_string) {
                $headings[] = '<h3>' . strip_tags($output_array[1][$key]) . '</h3>';
            }
            $input_lines = str_replace($output_array[0], $headings, $input_lines);
        }
    }

    return $input_lines;
}
