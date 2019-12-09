<?php
// if (is_singular('single-agency')){
//   $class1 = "agency__col";
//   $class2 = "agency__video";
// } elseif (is_singular('page-about')) {
//   $class1 = "overview__video-wrap";
//   $class2 = "overview__video";
// }
$video_poster = get_field( 'overview_video_poster');
$video_url = get_field( 'overview_video_url' );
?>

   <div class="overview__video" id="video-popup"
   style="background-image: url('<?php echo $video_poster; ?>');"
   >
