<?php

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Vimeo\Vimeo;



function file_exist_s3($path, $bucket) {

    require_once( __DIR__ . '/../../../config.php');
    require_once( __DIR__ . '/locallib.php');

    $s3 = get_cloudobj();

    $exist = $s3->doesObjectExist($bucket, $path);
    //echo $exist;
    if (isset($exist) && !empty($exist)) {
        return true;
    } else {
        return false;
    } 
}

function file_exist_vimeo($vimeoid, $bucket) {

    global $CFG;

    require_once( __DIR__ . '/../../../config.php');
    require_once( __DIR__ . '/../lib.php');
    require_once( __DIR__ . '/../locallib.php');
    require_once($CFG->dirroot . '/local/video_directory/cloud/vimeo/vendor/autoload.php');

    $vimeo = get_cloudobj();

    try {
        $response = $vimeo->request('/' . $bucket . '/' . $vimeoid, 'GET');
        //print_r($response);
        if ($response['status'] == 200){
            echo 'video exist: ' .$vimeoid . PHP_EOL;
            return true;
        } else {
            echo $response['body']['error']. PHP_EOL;
            return false;
        }
    } catch (Exception $e) {
        echo $e->getMessage() . PHP_EOL;
        return false;
    }
}


function delete_s3($path, $bucket) {

    require_once 'locallib.php';

    $s3 = get_cloudobj();
    $keyname = $path;

    try {
        echo 'Attempting to delete ' . $keyname . '...' . PHP_EOL;

        $result = $s3->deleteObject([
            'Bucket' => $bucket,
            'Key'    => $keyname
        ]);
        print_r($result);
        if ($result['DeleteMarker'] || $result['@metadata']['statusCode'] == 204){
            echo $keyname . ' was deleted or does not exist.' . PHP_EOL;
        } else {
            exit('Error: ' . $keyname . ' was not deleted.' . PHP_EOL);
        }
    }
    catch (S3Exception $e) {
        exit('Error: ' . $e->getAwsErrorMessage() . PHP_EOL);
    }
}

function delete_vimeo($vimeoid, $bucket) {

    global $CFG, $DB;

    require_once( __DIR__ . '/../../../config.php');
    require_once( __DIR__ . '/../lib.php');
    require_once( __DIR__ . '/../locallib.php');
    require_once($CFG->dirroot . '/local/video_directory/cloud/vimeo/vendor/autoload.php');

    $vimeo = get_cloudobj();
    try {
        $response = $vimeo->request('/' . $bucket . '/' . $vimeoid, array(), 'DELETE');
        if ($response['status'] == 204){
            echo $vimeoid . ' was deleted.' . PHP_EOL;
            $vimeo = $DB->delete_records('local_video_directory_vimeo', ['vimeoid' => $vimeoid]);
        } else if ($response['status'] == 404){
            echo $response['body']['error']. PHP_EOL;
        } else {
            exit('Error: ' . $vimeoid . ' was not deleted.' . PHP_EOL);
        }
    
    } catch (Exception $e) {
        echo $e->getMessage() . PHP_EOL;
    }
}