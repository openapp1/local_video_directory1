<?php
define('CLI_SCRIPT', 1);

// Script to upload videos from local cloud to vimeo cloud.
use Vimeo\Vimeo;
require_once( __DIR__ . '/../../../config.php');
require_once($CFG->dirroot . '/local/video_directory/cloud/locallib.php');
require_once(__DIR__ .'/../locallib.php');

global $DB;
$dirs = get_directories();
$origdir = $dirs['uploaddir'];
$streamingdir = $dirs['converted'];
 
$wait = $DB->execute('UPDATE {local_video_directory} SET convert_status = 4 WHERE convert_status = 1');

$sql = "SELECT *
        FROM {local_video_directory} AS v WHERE  v.id = 2829";
$videos =  $DB->get_records_sql($sql, []);


foreach ($videos as $video) {
    $filename = local_video_directory_get_filename($video->id);
    if (file_exists($streamingdir . $filename . ".mp4")) {
        upload($video->id, 'name' , $streamingdir . $filename . ".mp4");
    }
}

if ($cloudtype == 'Vimeo') {
    $vimeos = $DB->get_records('local_video_directory_vimeo' , array("streamingurl" => null));
    foreach ($vimeos as $vimeo) {
        set_data_vimeo($vimeo); 
    }
}
