<?php
if (!function_exists('import_jobs_from_xml'))   {

function import_jobs_from_xml() {
  require_once( ABSPATH . 'wp-admin/includes/post.php' );

  $xml = simplexml_load_file( "app/uploads/job-feed/jobs.xml") or die("Fail");

  $totaljobs = count($xml->entry);

    for ($x = 0; $x < 20; $x++) {
      $job_content =  $xml->entry[$x]->content;
      $totalspans = count($job_content->div->span);
      $job_title = $xml->entry[$x]->title;

      if($totalspans > 0) {

          $job_id = (string) $job_content->div->span[0];
          $job_link = (string) $xml->entry[$x]->id;

          $job_content_hash = md5($job_content->div->asXML());

          $job_title = str_replace($job_id . " - ", "", $job_title);

          echo $job_title;
          echo "<br/>";

          $args = array(
              'post_type'=>'job',
              'numberposts'=>-1,
              'meta_query' => array(
              array(
                  'key' => 'job_id',
                  'value' => $job_id,
                  'compare' => '=',
              )
          )
          );
          $job_check = get_posts( $args );

          if(count($job_check) == 0) {

              // Creates job post
              $job_post = array(
                  'post_title' => $job_title,
                  'post_content' => ' ',
                  'post_status' => 'publish',
                  'post_type' => 'job'
              );

              // Insert the post into the database
              $post_id = wp_insert_post($job_post);

              set_job_details($job_content, $totalspans, $post_id);

              // Save Application Link
              update_field('application_link', $job_link, $post_id);
              update_field('job_id', $job_id, $post_id);
              update_field('job_content_hash', $job_content_hash, $post_id);

              echo "Job Added";
              echo "<br/>";

          }
          else {
              $post_id = $job_check[0]->ID;

              $current_hash = get_field('job_content_hash', $post_id);

              if($job_content_hash != $current_hash){

                  set_job_details($job_content, $totalspans, $post_id);

                  update_field('application_link', $job_link, $post_id);
                  update_field('job_content_hash', $job_content_hash, $post_id);

                  echo "Job Updated";
                  echo "<br/>";
              }
              else {
                echo "No Changes";
                echo "<br/>";
              }
          }

      }
      else {
        echo "Error reading Job Data";
        echo "<br/>";
      }

    }





}

}

function set_job_details($job_content, $totalspans, $post_id) {

    $working_patterns = array();
    $job_locations = array();

    for ($y = 0; $y < $totalspans; $y++) {
        // Save Role Type Info
        if ($job_content->div->span[$y]->attributes()->itemprop[0] == "Role Type") {
            $role_type = (string) $job_content->div->span[$y];

            wp_set_object_terms($post_id, $role_type, 'role_type');

        }

        // Save Salary Info
        if ($job_content->div->span[$y]->attributes()->itemprop[0] == "Salary Range") {
            $salary = (string) $job_content->div->span[$y];
            wp_set_object_terms($post_id, $salary, 'salary_range');

        }

        // Save Working Pattern Info
        if ($job_content->div->span[$y]->attributes()->itemprop[0] == "Working Pattern") {
            array_push($working_patterns, (string) $job_content->div->span[$y]);
            wp_set_object_terms($post_id, $working_patterns, 'working_pattern');

        }

        // Save Job Locations Info
        if ($job_content->div->span[$y]->attributes()->itemprop[0] == "Building/Site") {
            array_push($job_locations, (string) $job_content->div->span[$y]);
            wp_set_object_terms($post_id, $job_locations, 'job_location');

        }

        // Save Location Info
        if ($job_content->div->span[$y]->attributes()->itemprop[0] == "City/Town") {
            $location = (string) $job_content->div->span[$y];
            update_field('location', $location, $post_id);
        }

        // Save Job Description Info
        if ($job_content->div->span[$y]->attributes()->itemprop[0] == "Job description Additional Information") {
            $job_info_string = $job_content->div->span[$y]->asXML();
            $update_post = array(
                'ID' => $post_id,
                'post_content' => $job_info_string
            );

            wp_update_post($update_post);
        }

        // Save Additional Info
        if ($job_content->div->span[$y]->attributes()->itemprop[0] == "Additional Information") {
            $job_add_info_string = $job_content->div->span[$y]->asXML();
            update_field('additional_information', $job_add_info_string, $post_id);

        }

    }

}
 ?>
