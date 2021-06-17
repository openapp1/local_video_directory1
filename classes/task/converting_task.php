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
/**
 * Class for converting videos task.
 * @copyright  2018 Yedidia Klein OpenApp Israel
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class converting_task extends \core\task\scheduled_task {
    public function get_name() {
        // Shown in admin screens.
        return get_string('pluginname', 'local_video_directory');
    }

    public function execute() {
        global $CFG;
        require_once($CFG->dirroot . '/local/video_directory/converting_task_local.php');
        require_once($CFG->dirroot . '/local/video_directory/converting_task_cloud.php');
        global $CFG;

        $cloudtype = get_config('local_video_directory_cloud', 'cloudtype');
        if ($cloudtype != 'None') {
            converting_task_cloud();
        } else {
            converting_task_local();
        }
    }
}
