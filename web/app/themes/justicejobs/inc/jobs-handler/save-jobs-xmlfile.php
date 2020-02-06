<?php

function saveJobsXMLFile($force_pull = false)
{
    if (get_option('jobs-cron-switch-input') !== '1') {
        return false;
    }

    // get admin email for messaging
    $to = get_option('admin_email');

    // should we run?
    if (!inside_schedule_window() && !$force_pull) {
        // fix potential broken 'cron is running' flag (due to nginx 504 timeout bug)
        if (is_2_hours_past_window() && get_option('jobs_request_cron_is_running') == true) {
            update_option('jobs_request_cron_is_running', false);
            jj_simple_mail($to, [
                '[Justice Jobs] Fixed broken flag',
                'BROKEN -> the jobs schedule was repaired.'
            ]);
        }
        return false;
    }

    if ($force_pull) {
        update_option('jobs_request_cron_is_running', false);
    }

    // check if this script is already running, bail if it is.
    if (get_option('jobs_request_cron_is_running', false)) {
        jj_simple_mail($to, [
            '[Justice Jobs] Getting Remote Data',
            'WARNING -> the jobs script is already running. A request to refresh the job list has failed.'
        ]);
        return false;
    }

    // we are ready to start, lock the script...
    update_option('jobs_request_cron_is_running', true);

    $url = "https://justicejobs.tal.net/vx/mobile-0/appcentre-1/brand-2/candidate/jobboard/vacancy/3/feed/structured";
    $tmp = get_temp_dir() . "jobs.xml";
    $file = "app/uploads/job-feed/jobs.xml";

    wp_remote_get($url, [
        'timeout' => 1800,
        'stream' => true,
        'filename' => $tmp
    ]);

    // let's check the data is xml
    if (!simplexml_load_file($tmp)) {
        // inform admin
        jj_simple_mail($to, [
            "[Justice Jobs] Security: error detected in jobs XML feed",
            "Please check the following URL for errors. The job feed load process failed in " . __FUNCTION__ . "\n\n" . $url
        ]);

        return false;
    }

    // copy the tmp file contents to system file
    if (copy($tmp, $file)) {
        // set flag to notify import process that data is refreshed
        update_option('jobs_request_has_updated', true);
        // fire off an email 'thumbs-up' to say all is well.
        jj_simple_mail($to, [
            '[Justice Jobs] Saving remote data',
            'SUCCESS -> jobs have been saved from remote server.'
        ]);

        // clean up tmp file
        unlink($tmp);
    }

    // unlock this script for next schedule window
    update_option('jobs_request_cron_is_running', false);
}
