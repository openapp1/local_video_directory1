<?php

use Aws\Common\Exception\MultipartUploadException;
use Aws\S3\MultipartUploader;
use Aws\S3\S3Client;
use Aws\Credentials\Credentials;
use Vimeo\Vimeo;

function file_exist_cloud($videoid, $filename) {

    global $CFG;

    require_once( __DIR__ . '/../../../config.php');
    require_once(__DIR__ . '/delete.php');

    $cloudtype = get_config('local_video_directory_cloud', 'cloudtype');
    $bucket = get_config('local_video_directory_cloud' , 'videobucket');
    $status;
    switch ($cloudtype) {
        case 'Azure': 
            $status = file_exist_s3($filename, $bucket);
            break;
        case 'Vimeo':
            $vimeoid = get_vimeoid_by_videoid($videoid); 
            $status = file_exist_vimeo($vimeoid, $bucket);
            break;
    }
    return $status;
}

function upload($videoid, $newname, $filepath) {

    global $CFG, $DB;

    require_once( __DIR__ . '/../../../config.php');
    require_once(__DIR__ . '/upload.php');

    $cloudtype = get_config('local_video_directory_cloud', 'cloudtype');
    $bucket = get_config('local_video_directory_cloud' , 'videobucket');

    switch ($cloudtype) {
        case 'Azure': 
            $status = upload_to_cloud_s3($newname,  $filepath);
            break;
        case 'Vimeo': 
            echo '---vimeo----';
            $newname = $DB->get_field('local_video_directory', 'orig_filename', ['id' => $videoid], $strictness= IGNORE_MULTIPLE);
            $status = upload_to_cloud_vimeo($videoid, $newname,  $filepath, $bucket);
            break;
        return;
    }
}
function download($videoid, $name, $outputpath) {

    global $CFG;

    require_once( __DIR__ . '/../../../config.php');
    require_once(__DIR__ . '/download.php');

    $cloudtype = get_config('local_video_directory_cloud', 'cloudtype');
    $bucket = get_config('local_video_directory_cloud' , 'videobucket');

    switch ($cloudtype) {
        case 'Azure':
            $status = download_from_cloud_s3($name, $outputpath);
            break;
        case 'Vimeo':
            $vimeoid = get_vimeoid_by_videoid($videoid);
            $status = download_from_cloud_vimeo($vimeoid, $outputpath, $bucket);
            break;
        return;
    }
}

function delete_from_cloud($videoid, $path) {

    global $CFG;

    require_once( __DIR__ . '/../../../config.php');
    require_once(__DIR__ . '/delete.php');

    $cloudtype = get_config('local_video_directory_cloud', 'cloudtype');
    $bucket = get_config('local_video_directory_cloud' , 'videobucket');

    switch ($cloudtype) {
        case 'Azure':
            $status = delete_s3($path, $bucket);
            break;
        case 'Vimeo':
            $vimeoid = get_vimeoid_by_videoid($videoid);
            $status = delete_vimeo($vimeoid, $bucket);
            break;
        return;
    }
}

function get_cloudobj() {
    require_once( __DIR__ . '/../../../config.php');
    global $CFG;

    $accesskey = get_config('local_video_directory_cloud' , 'accesskey');
    $accesssecret = get_config('local_video_directory_cloud' , 'accesssecret');
    $endpoint = get_config('local_video_directory_cloud' , 'endpoint');
    $cloudtype = get_config('local_video_directory_cloud' , 'cloudtype');
    $region = get_config('local_video_directory_cloud' , 'region');
    
    if ($accesskey == '0') {
        echo 'No cloud has been set';
        return;
    }
    // Require the amazon sdk.
    require_once($CFG->dirroot . '/local/aws/sdk/aws-autoloader.php');

    $return = null;

    switch($cloudtype){
        case 'Azure':
        {
                $credentials = new Credentials($accesskey, $accesssecret);

                $s3 = new S3Client([
                    'endpoint' => $endpoint,
                    //'debug' => true,
                    'region' => $region,
                    'version' => 'latest',
                    'use_path_style_endpoint' => true,
                    'credentials' => $credentials
                ]);
                $return = $s3;
        }; break;
        case 'Vimeo':
        {
                require_once($CFG->dirroot . '/local/video_directory/cloud/vimeo/vendor/autoload.php');

                $accesskey = get_config('local_video_directory_cloud' , 'accesskey'); // 34ec15438def608a917961f97655d1d4a7aa7fa7
                $accesssecret = get_config('local_video_directory_cloud' , 'accesssecret'); // d3TuwluamYyy5YxIFoyh3iIJYJLBfGpq69N/yBJph9x3L4GEiSnN1CUVKonkQWhT8keB78zrsFb93bo1a84KFnIDmSngY4HGVOb4EXMhn97n0HwBzJChNWvhfqwx5IRO
                $accesstoken =  get_config('local_video_directory_cloud' , 'accesstoken'); // d040ee7af225eab628c3512d9cb46825
                $endpoint = get_config('local_video_directory_cloud' , 'endpoint'); // https://api.vimeo.com/
                $bucket = get_config('local_video_directory_cloud' , 'videobucket'); //videos
                
                $vimeo = new Vimeo($accesskey, $accesssecret, $accesstoken);
                $return = $vimeo;
        }; break;
        default: $return = null;
    }
    return $return;
}

function get_streaming_cloud_vimeo($vimeoid, $outputpath) {

    global $CFG, $DB;

    require_once( __DIR__ . '/../../../config.php');
    require_once( __DIR__ . '/../lib.php');
    require_once( __DIR__ . '/../locallib.php');
    require_once($CFG->dirroot . '/local/video_directory/cloud/vimeo/vendor/autoload.php');

    $vimeo = get_cloudobj();
    $bucket = get_config('local_video_directory_cloud' , 'videobucket'); //'videos';

    if (isset($vimeoid)) {
        try {
            $response = $vimeo->request('/' . $bucket . '/' . $vimeoid, 'GET');
            $fileurl = $response['body']['files'][1]['link'];
            $thumbdurl = $response['body']['pictures'][1]['link'];

            echo "Get files complete: ". $fileurl.  PHP_EOL;
            echo "Get thumb complete: ". $thumbdurl.  PHP_EOL;
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    } else {
        echo 'not found vimeoid in DB' . PHP_EOL;
    }  
}


function get_vimeoid_by_videoid($videoid) {

    global $CFG, $DB;
    require_once( __DIR__ . '/../../../config.php');
    
    $vimeoid = $DB->get_field('local_video_directory_vimeo', 'vimeoid', ['videoid' => $videoid], $strictness= IGNORE_MULTIPLE );
    return $vimeoid;
}

function get_videoid_by_vimeoid($vimeoid) {

    global $CFG, $DB;
    require_once( __DIR__ . '/../../../config.php');
    
    $videoid = $DB->get_field('local_video_directory_vimeo', 'videoid', ['vimeoid' => $vimeoid], $strictness= IGNORE_MULTIPLE);
    return $videoid;
}

function set_data_vimeo($vimeo) {
    
    global $CFG, $DB;

    require_once( __DIR__ . '/../../../config.php');
    require_once( __DIR__ . '/../lib.php');
    require_once($CFG->dirroot . '/local/video_directory/cloud/vimeo/vendor/autoload.php');

    $vimeoobj = get_cloudobj();
    $bucket = get_config('local_video_directory_cloud' , 'videobucket'); //'videos';
    try {       
        $response = $vimeoobj->request('/' . $bucket . '/' . $vimeo->vimeoid, 'GET');
        $status = $response['body']['upload']['status'];
        if ($status == 'complete') {
            print_r($response);
            if (isset($response['body']['files'][0]['link'])) {
                $streamingurl = $response['body']['files'][0]['link'];
                echo "Get streamingurl complete: ". $streamingurl.  PHP_EOL;
                
                /*
                foreach ($response['body']['files'] as $file) {
                    print_r($file);
                    if ($file['quality'] = 'hls') {
                        $streaminghls = $file['quality'];
                        echo "Get streaminghls complete: ". $streaminghls.  PHP_EOL;
                    }
                }
                */
                if (isset($response['body']['files'][2]['link'])) {
                    $streaminghls = $response['body']['files'][2]['link'];
                    echo "Get streaminghls complete: ". $streaminghls.  PHP_EOL;
                }
                
                if (isset($response['body']['pictures']['sizes'][8]['link'])) {
                    $thumburl = $response['body']['pictures']['sizes'][8]['link'];
                    echo "Get thumb complete: ". $thumburl.  PHP_EOL;
                }
            }
            
            $v = $DB->get_record('local_video_directory_vimeo', ['videoid' => $vimeo->videoid, 'vimeoid' => $vimeo->vimeoid]);
            $v->streamingurl = isset($streamingurl) ? $streamingurl: null;
            $v->streaminghls = isset($streaminghls) ? $streaminghls: null;
            $v->thumburl = isset($thumburl) ? $thumburl: null;
            $DB->update_record('local_video_directory_vimeo', $v);
        } 
    } catch (Exception $e) {
        echo $e->getMessage() . PHP_EOL;
    }
}

function get_data_vimeo($videoid) {
    
    global $CFG, $DB;

    require_once( __DIR__ . '/../../../config.php');
    
    $sql = "SELECT * 
            FROM {local_video_directory_vimeo}
            WHERE videoid = ?
            ORDER BY timecreated DESC
            LIMIT 1";
    $vimeo = $DB->get_record_sql($sql, [$videoid]);
    return $vimeo;
}
