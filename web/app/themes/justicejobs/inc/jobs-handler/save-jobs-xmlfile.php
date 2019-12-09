<?php

function saveJobsXMLFile(){
  // $url = "https://justicejobs.tal.net/vx/mobile-0/appcentre-1/brand-2/candidate/jobboard/vacancy/3/feed";
  $url = "https://justicejobs.tal.net/vx/mobile-0/appcentre-1/brand-2/candidate/jobboard/vacancy/3/feed/structured";
  $file = "app/uploads/job-feed/jobs.xml";
  $fp = fopen($file, "w");
  $ch = curl_init();
  $timeout = 30*60; //15mins

  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_NOPROGRESS, FALSE);
  curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'progressCallback');
  curl_setopt($ch, CURLOPT_BUFFERSIZE, (1024*1024*512));
  curl_setopt($ch, CURLOPT_FAILONERROR, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
  // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); //wait indefinitely to connect
  // curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
  curl_setopt($ch, CURLOPT_FILE, $fp);

  //to get log for debugging
  curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
  $verbose = fopen('app/uploads/job-feed/verbose_log.txt', 'w+');
  curl_setopt($ch, CURLOPT_STDERR, $verbose);

  curl_exec($ch);
  echo "curl_exec was succesful";

  curl_close($ch);
  fclose($fp);
}

function progressCallback($curl_resource, $download_size, $downloaded, $upload_size, $uploaded) {
  echo "File size: " , $download_size;
  echo "Downloaded: ", $downloaded;
  echo "<br>";
}



 ?>
