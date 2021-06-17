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
 * Upload subtitles
 *
 * @package    local_video_directory
 * @copyright  2017 Yedidia Klein <yedidia@openapp.co.il>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once( __DIR__ . '/../../config.php');
require_login();
defined('MOODLE_INTERNAL') || die();
require_once('locallib.php');
require_once("$CFG->libdir/formslib.php");
require_once($CFG->dirroot . '/local/video_directory/cloud/locallib.php');

if (!CLI_SCRIPT) {
    require_login();
    // Check if user have permissionss.
    $context = context_system::instance();

    if (!has_capability('local/video_directory:video', $context) && !is_video_admin($USER)) {
        die("Access Denied. You must be a member of the designated cohort. Please see your site admin.");
    }
}

$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('vimeoupdates', 'local_video_directory'));
$PAGE->set_title(get_string('vimeoupdates', 'local_video_directory'));
$PAGE->set_url('/local/video_directory/vimeoupdates.php');
$PAGE->set_pagelayout('standard');

$PAGE->navbar->add(get_string('pluginname', 'local_video_directory'), new moodle_url('/local/video_directory/'));
$PAGE->navbar->add(get_string('vimeoupdates', 'local_video_directory'));
$PAGE->requires->css('/local/video_directory/style.css');

class vimeoupdates_form extends moodleform {
    public function definition() {
        global $CFG, $DB;
        $dirs = get_directories();
        $id = required_param('id', PARAM_INT);
        $type = required_param('type', PARAM_RAW);

        $mform = $this->_form;
        $mform->addElement('html', '<div class="alert alert-warning alert-block fade in">'.
                                get_string('updatevimeo' . $type, 'local_video_directory').'</div>');

        $mform->addElement('hidden', 'id', $id);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'type', $type);
        $mform->setType('type', PARAM_RAW);

        $buttonarray = array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', get_string('update'));
        $buttonarray[] = $mform->createElement('cancel', 'cancel', get_string('cancel'));
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
    }

    public function validation($data, $files) {
        return array();
    }
}

$mform = new vimeoupdates_form();

if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot . '/local/video_directory/list.php');
} else if ($fromform = $mform->get_data()) {
    $vimeo = $DB->get_record('local_video_directory_vimeo' , array("videoid" => $fromform->id));
    set_data_vimeo($vimeo); 
    redirect($CFG->wwwroot . '/local/video_directory/list.php');
} else {
    echo $OUTPUT->header();
    $mform->display();
}

echo $OUTPUT->footer();
