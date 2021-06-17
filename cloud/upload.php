<?php

// define('CLI_SCRIPT', true);

use Aws\Common\Exception\MultipartUploadException;
use Aws\S3\MultipartUploader;
use Aws\S3\S3Client;
use Aws\Credentials\Credentials;
use Vimeo\Vimeo;


function upload_to_cloud_s3( $newname, $filepath) {
    
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

    $keyname = $newname;

    $credentials = new Credentials($accesskey, $accesssecret);

    $s3 = new S3Client([
        'endpoint' => $endpoint,
        //'debug' => true,
        'region' => 'eu-central-1',
        'version' => 'latest',
        'use_path_style_endpoint' => true,
        'credentials' => $credentials
    ]);

    // Prepare the upload parameters.
    $uploader = new MultipartUploader($s3, $filepath, [
        'bucket' => $bucket,
        'key'    => $keyname
    ]);

    // Perform the upload.
    try {
        $result = $uploader->upload();
        echo "Upload complete: {$result['ObjectURL']}" . PHP_EOL;
    } catch (MultipartUploadException $e) {
        echo $e->getMessage() . PHP_EOL;
    }
}

function upload_to_cloud_vimeo($videoid, $newname, $filepath, $bucket) {
    
    global $CFG, $DB;
    
    require_once( __DIR__ . '/../../../config.php');
    require_once( __DIR__ . '/../lib.php');
    require_once( __DIR__ . '/../locallib.php');
    require_once($CFG->dirroot . '/local/video_directory/cloud/vimeo/vendor/autoload.php');

    $vimeo = get_cloudobj();

    try {
        
        $uri = $vimeo->upload($filepath, array(
            "name" => $newname,
            "description" => "The description goes here."
        ));
        echo "Upload complete: " . $uri . PHP_EOL;
        //$response = $vimeo->request($uri . '?fields=link');
        //echo "Your video link is: " . $response['body']['link'] . PHP_EOL;

        $parameters = explode("/",$uri);
        $vimeoid = $parameters[2];

        //Insert data to DB
       
        $response = $vimeo->request('/' . $bucket . '/' . $vimeoid, 'GET');
        $status = $response['body']['upload']['status'];
        if ($status == 'complete') {
            $streamingurl = $response['body']['files'][1]['link'];
            $thumburl = $response['body']['pictures'][1]['link'];
            echo "Get files complete: ". $streamingurl.  PHP_EOL;
            echo "Get thumb complete: ". $thumburl.  PHP_EOL;
        }

        $record = new stdClass();
        $record->videoid = $videoid;
        $record->vimeoid = $vimeoid;
        $record->streamingurl = isset($streamingurl) ? $streamingurl: null;
        $record->thumburl = isset($thumburl) ? $thumburl: null;
        $record->timecreated = time();
        $record = $DB->insert_record('local_video_directory_vimeo', $record);
        
    } catch (Exception $e) {
        echo $e->getMessage() . PHP_EOL;
    }
}
