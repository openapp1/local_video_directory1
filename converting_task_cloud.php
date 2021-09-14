<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * Converting Task.
 *
 * @package    local_video_directory
 * @copyright  2018 Yedidia Klein OpenApp Israel
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_video_directory\task;
defined('MOODLE_INTERNAL') || die();

use Aws\Common\Exception\MultipartUploadException;
use Aws\S3\MultipartUploader;
use Aws\S3\S3Client;
use Aws\Credentials\Credentials;
use Aws\S3\StreamWrapper;
use Aws\S3\S3ClientInterface;

function converting_task_cloud() {
    global $CFG , $DB;
    require_once($CFG->dirroot . '/local/video_directory/locallib.php');
    require_once($CFG->dirroot . '/local/video_directory/lib.php');
    require_once($CFG->dirroot . '/local/video_directory/cloud/locallib.php');
    $dirs = get_directories();
    $settings = get_settings();
    $streamingurl = $settings->streaming . '/';
    $ffmpeg = $settings->ffmpeg;
    $ffprobe = $settings->ffprobe;
    $ffmpegsettings = '-strict -2 -c:v libx264 -profile:v high -pix_fmt yuv420p -crf 22 -c:a aac -movflags faststart -vf "scale=trunc(iw/2)*2:trunc(ih/2)*2"';
    $thumbnailseconds = $settings->thumbnail_seconds;
    $php = $settings->php;
    $multiresolution = $settings->multiresolution;
    $resolutions = $settings->resolutions;
    $origdir = $dirs['uploaddir'];
    $streamingdir = $dirs['converted'];
    $encoded = null;
    $cloudobj = get_cloudobj();
    $bucket = get_config('local_video_directory_cloud' , 'videobucket');
    $cloudtype = get_config('local_video_directory_cloud', 'cloudtype');
    $seconds = array(1, 3, 7, 12, 20, 60, 120);
    
    if ($cloudtype == 'Vimeo') {
        $vimeos = $DB->get_records('local_video_directory_vimeo' , array("streamingurl" => null));
        foreach ($vimeos as $vimeo) {
            set_data_vimeo($vimeo); 
        }
    }
    
    // Check if we've to convert videos.
    $videos = $DB->get_records('local_video_directory' , array("convert_status" => 1));
    // Move all video that have to be converted to Waiting.. state (4) just to make sure that there is not
    // multiple cron that converts same files.
    $wait = $DB->execute('UPDATE {local_video_directory} SET convert_status = 4 WHERE convert_status = 1');
    foreach ($videos as $video) {
        // Update convert_status to 2 (Converting....).
        $record = array("id" => $video->id , "convert_status" => "2");
        $update = $DB->update_record("local_video_directory" , $record);
        $encoded = null;
        // If we have a previous version - save the version before encoding.
        if ($cloudtype != 'Vimeo') {
            $res = file_exist_cloud($video->id, local_video_directory_get_filename($video->id) . ".mp4");
            if (isset($res) && !empty($res)) {
                $oldname = local_video_directory_get_filename($video->id);
                // On restore dont create new version
                if (!$DB->record_exists('local_video_directory_vers', ['file_id'=> $video->id, 'filename' => $oldname])) {
                    $time = time();
                    $oldname = local_video_directory_get_filename($video->id);
                    // Delete Thumbs.
                    //array_map('unlink', glob($streamingdir . local_video_directory_get_filename($video->id) . "*.png"));
                    delete_from_cloud($video->id, $streamingdir . local_video_directory_get_filename($video->id) . "*.png");
                    // Delete Multi resolutions.
                    //array_map('unlink', glob($dirs['multidir'] . local_video_directory_get_filename($video->id) . "_*.mp4"));
                    delete_from_cloud($video->id, $dirs['multidir'] . local_video_directory_get_filename($video->id) . "_*.mp4");

                    // Delete from multi table.
                    $DB->execute('DELETE FROM {local_video_directory_multi} WHERE video_id = ?', [$video->id]);
                    // Write to version table.
                    $record = array('datecreated' => $time, 'file_id' => $video->id, 'filename' => $oldname);
                    print_r($record);
                    echo "[local_video_directory] Inserting version $time to local_video_directory_vers for id: $video->id \n";
                    $insertid = $DB->insert_record('local_video_directory_vers', $record);
                }
            }
        }

        if (file_exists($ffmpeg)) {
            if (file_exists($ffprobe)) {
                // Get video length.
                $videocodec = $ffprobe ." -v error -select_streams v:0 -show_entries stream=codec_name -of default=noprint_wrappers=1" .
                    ":nokey=1 " . escapeshellarg($origdir . $video->id );
                $audiocodec = $ffprobe ." -v error -select_streams a:0 -show_entries stream=codec_name -of default=noprint_wrappers=1" .
                    ":nokey=1 " . escapeshellarg($origdir . $video->id );
                $firefoxbug = $ffprobe ." -v error -select_streams v:0 -show_entries stream=profile -of default=noprint_wrappers=1" .
                    ":nokey=1 " . escapeshellarg($origdir . $video->id );
                $videocodecval = exec($videocodec);
                $audiocodecval = exec($audiocodec);
                $firefoxbugval = exec( $firefoxbug);
                if (strpos($videocodecval , '264') !== false && $audiocodecval == 'aac' && strpos($firefoxbugval, 'Predictive') === false) {
                    echo 'not need to convert';
                    if (copy( $origdir . $video->id , $streamingdir . $video->id . ".mp4")) {
                        unlink($origdir . $video->id );
                    } else {
                        $convert = '"' .$ffmpeg. '" -hide_banner -loglevel warning -i ' .escapeshellarg($origdir.$video->id).' '
                        . $ffmpegsettings . ' '
                        . escapeshellarg($streamingdir . $video->id . ".mp4");
                        $exec = exec($convert);
                        echo " converted";
                    }
                } else {
                    $convert = '"' .$ffmpeg. '" -hide_banner -loglevel warning -i ' .escapeshellarg($origdir . $video->id) . ' '
                    . $ffmpegsettings . ' '
                    . escapeshellarg($streamingdir . $video->id . ".mp4");
                    $exec = exec($convert);
                    echo " converted!!";
                }
            } else {
                echo "Ffprobe is not configured well, No such file : " . $ffprobe . "\n";
            }
            // Convert encoded file to hashed name and directory.
            $contenthash = sha1_file($streamingdir . $video->id . ".mp4");
            $hashdirectory = substr($contenthash, 0, 2);
            if (!is_dir($streamingdir . $hashdirectory)) {
                mkdir($streamingdir . $hashdirectory);
            }
            $cloudmooviefile = $hashdirectory . '/' . $contenthash . '.mp4';
            $encoded = $streamingdir . $video->id . ".mp4";
            // If this video already exist - not upload againe.
            //if (!file_exist_cloud($video->id, $streamingurl . local_video_directory_get_filename($video->id) . ".mp4")) {
            upload($video->id, $cloudmooviefile , $streamingdir . $video->id . ".mp4");
            //}
        } else {
            echo "Ffmpeg is not configured well, No such file : " . $ffmpeg . "\n";
        }
        // Check if was converted.
        if (file_exists($ffprobe)) {
            // Get video length.
            $lengthcmd = $ffprobe ." -v error -show_entries format=duration -sexagesimal -of default=noprint_wrappers=1" .
                ":nokey=1 " . escapeshellarg($encoded);
            $lengthoutput = exec( $lengthcmd );
            // Remove data after .
            $arraylength = explode(".", $lengthoutput);
            $length = $arraylength[0];
        } else {
            echo "Ffprobe is not configured well, No such file : " . $ffprobe . "\n";
        }

        $lengththumb = $length ? $length : '3:00:00'; // In case present but falseish.
        $lengththumb = strtotime("1970-01-01 $lengththumb UTC");

        if ($encoded != null) {

            if ($cloudtype != 'Vimeo') {
                // Get Video Thumbnail.
                if (is_numeric($thumbnailseconds)) {
                    $alreadythere = array_search($thumbnailseconds, $seconds);
                    if (!$alreadythere) {
                        $seconds[] = $thumbnailseconds;
                    }
                }
                if (file_exists($ffmpeg)) {
                    foreach ($seconds as $second) {
                        if ($second < $lengththumb) {
                            $timing = gmdate("H:i:s", $second );
                            $thumb = '"' . $ffmpeg . '" -hide_banner -loglevel warning -i ' . $encoded .
                                    " -ss " . escapeshellarg($timing) . " -vframes 1 " .
                                    escapeshellarg($streamingdir . $video->id . '-'. $second . ".png");
                            $thumbmini = '"' . $ffmpeg . '" -hide_banner -loglevel warning -i ' . $encoded .
                                    " -ss " . escapeshellarg($timing) . " -vframes 1 -vf scale=100:-1 " .
                                    escapeshellarg($streamingdir . $video->id . '-'. $second . "-mini.png");                   
                            $exec = exec($thumb);
                            $exec = exec($thumbmini);

                            $cloudthumbfile = $hashdirectory . '/' . $contenthash . '-'. $second;
                            upload($video->id, $cloudthumbfile . '.png' , $streamingdir . $video->id . '-'. $second . ".png");
                            upload($video->id, $cloudthumbfile . '-mini.png', $streamingdir . $video->id . '-'. $second . "-mini.png");
                            
                            if (file_exists($streamingdir . $video->id . '-'. $second . ".png")) {
                                unlink($streamingdir . $video->id . '-'. $second . ".png");
                            }
                            if (file_exists($streamingdir . $video->id . '-'. $second . "-mini.png")) {
                                unlink($streamingdir . $video->id . '-'. $second . "-mini.png");
                            } 
                        }
                    }
                } else {
                    echo "Ffmpeg is not configured well, No such file : " . $ffmpeg . "\n";
                }
            }
            $metadata = array();
            $metafields = array("height" => "stream=height", "width" => "stream=width", "size" => "format=size");
            foreach ($metafields as $key => $value) {
                $metadata[$key] = exec($ffprobe . " -v error -show_entries " . $value .
                    " -of default=noprint_wrappers=1:nokey=1 " . $encoded);
            }
            // Update that converted and streaming URL.
            $record = array("id" => $video->id,
                            "convert_status" => "3",
                            "streamingurl" => $streamingurl . $video->id . ".mp4",
                            "filename" => $hashdirectory . '/' . $contenthash,
                            "thumb" => $video->id,
                            "length" => $length,
                            "height" => $metadata['height'],
                            "width" => $metadata['width'],
                            "size" => $metadata['size'],
                            "timecreated" => time(),
                            "timemodified" => time()
                            );
            $update = $DB->update_record("local_video_directory", $record);
            // Delete original uploaded file.
            if (file_exists($encoded)) {
                unlink($streamingdir . $video->id . ".mp4");
            }
            // Delete original uploaded file.
            if (file_exists($origdir . $video->id)) {
                unlink($origdir . $video->id);
            }
            // Sent notification email.
            if (get_config('local_video_directory' , 'sendemailwhenready')) {
                $userid = $DB->get_field("local_video_directory" , 'owner_id', array('id' => $video->id) );
                $user = $DB->get_record('user', array('id' => $userid));
                $from = get_config('noreplyaddress');
                $subject = get_string('emailsubject' , 'local_video_directory' );
                $msg = get_string('emailmsg', 'local_video_directory' );
                if ($user) {
                    email_to_user( $user,  $from,   $subject,  $msg  .$video->id );
                }
            }//>
        } else {
            // Update that converted and streaming URL.
            $record = array("id" => $video->id, "convert_status" => "5");
            $update = $DB->update_record("local_video_directory", $record);
        }
    }
    // Take care of wget table.
    $wgets = $DB->get_records('local_video_directory_wget', array("success" => 0));
    if ($wgets) {
        foreach ($wgets as $wget) {
            $record = array('id' => $wget->id, 'success' => 1);
            $update = $DB->update_record("local_video_directory_wget", $record);
            $filename = basename($wget->url);
            if (!filter_var($wget->url, FILTER_VALIDATE_URL)) {
                continue;
            }
            if ((strstr($wget->url, 'youtube')) || (strstr($fromform->url, 'youtu.be'))) {
                $uniqid = uniqid('', true);
                mkdir($dirs['wgetdir'] . "/" . $uniqid);
                exec($settings->youtubedl . " -q -o " . $dirs['wgetdir'] . "/" . $uniqid . "/'%(title)s.%(ext)s' "
                    . $wget->url);
                $files = scandir($dirs['wgetdir'] . "/" . $uniqid, 1);
                $filename = $files[0];
                $record = array('orig_filename' => $filename, 'owner_id' => $wget->owner_id, 'uniqid' => uniqid('', true),
                    'private' => 1);
                $lastinsertid = $DB->insert_record('local_video_directory', $record);
                if (copy($dirs['wgetdir'] . "/" . $uniqid . "/" . $filename, $dirs['uploaddir'] . $lastinsertid)) {
                    unlink($dirs['wgetdir'] . "/" . $uniqid . "/" . $filename);
                    rmdir($dirs['wgetdir'] . "/" . $uniqid);
                    $sql = "UPDATE {local_video_directory_wget} SET success = 2 WHERE url = ?";
                    $DB->execute($sql, array($wget->url));
                }
            } else {
                echo "Downloading $wget->url to" . $dirs['wgetdir'];
                echo "Filename is $filename";
                file_put_contents($dirs['wgetdir'] . $filename, fopen($wget->url, 'r'));
                // Move to mass directory once downloaded.
                if (copy($dirs['wgetdir'] . $filename, $dirs['massdir'] . $filename)) {
                    unlink($dirs['wgetdir'] . $filename);
                    $sql = "UPDATE {local_video_directory_wget} SET success = 2 WHERE url = ?";
                    $DB->execute($sql, array($wget->url));
                }
            }
            // Doing one download per cron.
            break;
        }
    }
    if ($multiresolution) {
        // Create multi resolutions streams.
        $videos = $DB->get_records("local_video_directory", array('convert_status' => 3));
        foreach ($videos as $video) {
            local_video_directory_create_dash($video->id, $dirs['converted'], $dirs['multidir'], $ffmpeg, $resolutions);
        }
    }
    // CROPs.
    $crops = $DB->get_records("local_video_directory_crop", array('state' => 0));
    local_video_directory_studio_action_cloud($crops, "crop");
    // Merge.
    $merge = $DB->get_records("local_video_directory_merge", array('state' => 0));
    local_video_directory_studio_action_cloud($merge, "merge");
    // Cut.
    $cut = $DB->get_records("local_video_directory_cut", array('state' => 0));
    local_video_directory_studio_action_cloud($cut, "cut");
    // Cat.
    $cat = $DB->get_records("local_video_directory_cat", array('state' => 0));
    local_video_directory_studio_action_cloud($cat, "cat");
    // Speed.
    $speed = $DB->get_records("local_video_directory_speed", array('state' => 0));
    local_video_directory_studio_action_cloud($speed, "speed");
}
