<?php

// define('CLI_SCRIPT', true);

use Aws\Common\Exception\MultipartUploadException;
use Aws\S3\MultipartUploader;
use Aws\S3\S3Client;
use Aws\Credentials\Credentials;
use Vimeo\Vimeo;


function download_from_cloud_s3($name, $outputpath) {

    require_once( __DIR__ . '/../../../config.php');
    require_once( __DIR__ . '/../lib.php');
    require_once( __DIR__ . '/../locallib.php');

    global $CFG;

    $accesskey = get_config('local_video_directory_cloud' , 'accesskey'); //'QJ9RN216N0IJVPAPD8DW';
    $accesssecret = get_config('local_video_directory_cloud' , 'accesssecret'); //'5LJAMfD0GbqMICoDJ51wbogMA88lHwCsUXk6nCSw';
    $endpoint = get_config('local_video_directory_cloud' , 'endpoint'); //http://s3.eu-central-1.wasabisys.com/;
    $bucket = get_config('local_video_directory_cloud' , 'videobucket'); //'videodirectory';

    if ($accesskey == '0') {
        echo 'No cloude has been set';
        return;
    }

    // Require the amazon sdk.
    require_once($CFG->dirroot . '/local/aws/sdk/aws-autoloader.php');

    $path = $endpoint . $bucket . '/' . $name;
    $wget = get_config('local_video_directory' , 'wgeturl');


    if (file_exists($wget)) {
        $cmd = $wget . " -q \"$path\" -O $outputpath";
        print_r("\n" . '-----------------' . $cmd . "\n");

        exec($cmd);
    }
}

function download_from_cloud_vimeo($vimeoid, $outputpath, $bucket) {

    global $CFG, $DB;

    require_once( __DIR__ . '/../../../config.php');
    require_once( __DIR__ . '/../lib.php');
    require_once( __DIR__ . '/../locallib.php');
    require_once($CFG->dirroot . '/local/video_directory/cloud/vimeo/vendor/autoload.php');

    $vimeo = get_cloudobj();
    
    if (isset($vimeoid)) {
        try {
            $response = $vimeo->request('/' . $bucket . '/' . $vimeoid, 'GET');
            print_r($response);
            $downloadurl = $response['body']['download'][1]['link'];
            echo "Download complete: ". $downloadurl.  PHP_EOL;
    
            $wget = get_config('local_video_directory' , 'wgeturl');
            if (file_exists($wget)) {
                $cmd = $wget . " -q \"$downloadurl\" -O $outputpath";
                exec($cmd);
            }
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    } else {
        echo 'not found vimeoid in DB' . PHP_EOL;
    }  
}
