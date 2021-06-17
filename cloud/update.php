<?php
define('CLI_SCRIPT', 1);

// Script to set data for videos in vimeo cloud.
use Vimeo\Vimeo;
require_once( __DIR__ . '/../../../config.php');
require_once($CFG->dirroot . '/local/video_directory/cloud/locallib.php');
require_once(__DIR__ .'/../locallib.php');

global $DB;
$vimeos = $DB->get_records('local_video_directory_vimeo' , array("streaminghls" => null));
//$vimeos = $DB->get_records('local_video_directory_vimeo' , array("videoid"=> 2896));
    foreach ($vimeos as $vimeo) {
        set_data_vimeo($vimeo);
    }
