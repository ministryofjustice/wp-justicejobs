<?php

if (!function_exists('addJobPost'))   {

function addJobPost() {
  require_once( ABSPATH . 'wp-admin/includes/post.php' );

  $allposts= get_posts( array('post_type'=>'job','numberposts'=>-1) );
  foreach ($allposts as $eachpost) {
    wp_delete_post( $eachpost->ID, true );
  }



  $xml = simplexml_load_file( "app/uploads/job-feed/jobs.xml") or die("Fail");

  $totaljobs = count($xml->entry);
  $job_id;
  $job_title;
  $job_link;
  $salary;
  $working_patterns = array();
  $job_locations = array();


    for ($x = 0; $x < $totaljobs; $x++) {
      $totalspans = count($xml->entry[$x]->content->div->span);
      $job_id = $xml->entry[$x]->content->div->span[0];
      $job_title = $xml->entry[$x]->title;
      $job_link = (string) $xml->entry[$x]->id;

      echo $job_id;
      echo $job_title;


      if (post_exists($job_title)) {

        echo 'woot woot!';

      } else {

        // Creates job post
        $job_post = array(
          'post_title'    => $job_title,
          'post_content'  => ' ',
          'post_status'   => 'publish',
          'post_type'     => 'job'
        );

        // Insert the post into the database
        $post_id = wp_insert_post( $job_post );

        for ($y = 0; $y < $totalspans; $y++) {
          // Save Role Type Info
          if ($xml->entry[$x]->content->div->span[$y]->attributes()->itemprop[0] == "Role Type") {
            $role_type = (string) $xml->entry[$x]->content->div->span[$y];

            wp_set_object_terms( $post_id, $role_type, 'role_type' );

          }

          // Save Salary Info
          if ($xml->entry[$x]->content->div->span[$y]->attributes()->itemprop[0] == "Salary Range") {
            $salary = (string) $xml->entry[$x]->content->div->span[$y];
            wp_set_object_terms( $post_id, $salary, 'salary_range' );

          }

          // Save Working Pattern Info
          if ($xml->entry[$x]->content->div->span[$y]->attributes()->itemprop[0] == "Working Pattern") {
            array_push($working_patterns, (string) $xml->entry[$x]->content->div->span[$y]);
            wp_set_object_terms( $post_id, $working_patterns, 'working_pattern' );

          }

          // Save Job Locations Info
          if ($xml->entry[$x]->content->div->span[$y]->attributes()->itemprop[0] == "Building/Site") {
            array_push($job_locations, (string) $xml->entry[$x]->content->div->span[$y]);
            wp_set_object_terms( $post_id, $job_locations, 'job_location' );

          }

          // Save Location Info
          if ($xml->entry[$x]->content->div->span[$y]->attributes()->itemprop[0] == "City/Town") {
            $location = (string) $xml->entry[$x]->content->div->span[$y];
            update_field( 'location', $location, $post_id );
          }

          // Save Job Description Info
          if ($xml->entry[$x]->content->div->span[$y]->attributes()->itemprop[0] == "Job description Additional Information") {
            $job_info_string = $xml->entry[$x]->content->div->span[$y]->asXML();
            $update_post = array(
                'ID'           => $post_id,
                'post_content' => $job_info_string
            );

            wp_update_post( $update_post );
          }

          // Save Additional Info
          if ($xml->entry[$x]->content->div->span[$y]->attributes()->itemprop[0] == "Additional Information") {
            $job_add_info_string = $xml->entry[$x]->content->div->span[$y]->asXML();
            update_field( 'additional_information', $job_add_info_string, $post_id );

          }

        }

        // Save Application Link
        update_field( 'application_link', $job_link, $post_id );

      }

      $working_patterns = array();
      $job_locations = array();


    }

}

}

 ?>
