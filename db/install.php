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
 * @package    local_video_directory
 * @copyright  2017 Yedidia Klein <yedidia@openapp.co.il>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/accesslib.php');
$role = $DB->get_record('role', array('shortname' => 'local_video_directory'));
if (empty($role)) {
    $roleid = create_role('local_video_directory', 'local_video_directory', 'video system role');
}

$role = $DB->get_record('role', array('shortname' => 'local_video_directory_admin'));
if (empty($role)) {
    $roleid = create_role('local_video_directory_admin', 'local_video_directory_admin', 'video admin system role');
}


function xmldb_local_video_directory_install() {

}