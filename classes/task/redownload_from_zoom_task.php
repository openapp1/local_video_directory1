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
 * Google Speech Task.
 *
 * @package    local_video_directory
 * @copyright  2018 Yedidia Klein OpenApp Israel
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


namespace local_video_directory\task;
defined('MOODLE_INTERNAL') || die();
require_once( __DIR__ . '/../../locallib.php');
class redownload_from_zoom_task extends \core\task\scheduled_task {

    public function get_name() {
        return get_string('redownload_from_zoom', 'local_video_directory');
    }

    public function execute() {
        global $DB;

        $sql = " SELECT v.*, vz.video_original_name
            FROM {local_video_directory} AS v JOIN {local_video_directory_zoom} AS vz
            ON v.id = vz.video_id
            WHERE v.convert_status = 5";
        $videos = $DB->get_records_sql($sql, null, $limitfrom = 0, $limitnum = 0);

        $dirs = get_directories();
        foreach ($videos as $video) {

            $delme = 0;

            $redown = 0;
            $sql = "SELECT *
            FROM {zoom_redownload_video}
            WHERE video_original_name LIKE '" . $video->video_original_name . "'";
            " AND video_original_name IS NOT NULL
             LIMIT 1";
            $redown = $DB->get_record_sql($sql, []);
            if ($redown != array()) { // Has been redownloaded in past.
                if ($redown->counter < 5 ) { // Redownload.

                    $delme = 1;
                    $redown->counter += 1;
                    $redown->video_old_id = $video->id;
                    $DB->update_record('zoom_redownload_video', $redown);
                }
            } else { // Did not redownload in past. it is first time.
                $delme = 1;
                $redown = new \stdclass();
                $redown->video_original_name = $video->video_original_name;
                $redown->video_old_id = $video->id;
                $redown->counter = 1;
                $DB->insert_record('zoom_redownload_video', $redown);
            }

            if ($delme == 0) {
                continue;
            } else {
                $filename = local_video_directory_get_filename($video->id);
                $where = array("video_id" => $video->id);
                $multifilenames = $DB->get_records('local_video_directory_multi' , $where);
                $name = $video->orig_filename;
                $where = array("id" => $video->id);
                $deleted = $DB->delete_records('local_video_directory', $where);

                $videoconverted = $dirs['converted'] . $filename . '.mp4';

                $samevideos = $DB->get_records('local_video_directory' , ['filename' => $filename]);
                if (file_exists($videoconverted) && $samevideos == array()) {
                    unlink($videoconverted);
                }
                foreach ($multifilenames as $multi) {
                    $videomulti = $dirs['multidir'] .  $multi->filename;
                    if (file_exists($videomulti) && $samevideos == array()) {
                        unlink($videomulti);
                    }
                }

                // Delete zoom.
                $where = array('video_id' => $video->id);
                $DB->delete_records('local_video_directory_zoom', $where);

                echo "\n" . $name .  ' -DELETED' . "\n";
            }
        }

        /*$zooms = $DB->get_records('local_video_directory_zoom', []);

        foreach ($zooms as $zoom) {

            $video = $DB->get_record('local_video_directory', ['id' => $zoom->video_id]);
            if(isset($video)) {
                $zoom->video_original_name = $video->orig_filename;
                $DB->update_record("local_video_directory_zoom" , $zoom);
            }
        }*/
        



    }
}