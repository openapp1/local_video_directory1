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

function xmldb_local_video_directory_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2017040403) {

        // Define table local_video_multi to be created.
        $table = new xmldb_table('local_video_directory_multi');

        // Adding fields to table local_video_directory_multi.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('video_id', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('width', XMLDB_TYPE_INTEGER, '15', null, null, null, null);
        $table->add_field('height', XMLDB_TYPE_INTEGER, '15', null, null, null, null);
        $table->add_field('size', XMLDB_TYPE_INTEGER, '20', null, null, null, null);
        $table->add_field('filename', XMLDB_TYPE_CHAR, '200', null, XMLDB_NOTNULL, null, null);
        $table->add_field('datecreated', XMLDB_TYPE_INTEGER, '20', null, null, null, null);
        $table->add_field('datemodified', XMLDB_TYPE_INTEGER, '20', null, null, null, null);

        // Adding keys to table local_video_directory_multi.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for local_video_directory_multi.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        $table = new xmldb_table('local_video_directory');
        $field = new xmldb_field('height', XMLDB_TYPE_INTEGER, '13', null, null, null, null, 'length');
        // Conditionally launch add field height.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('width', XMLDB_TYPE_INTEGER, '13', null, null, null, null, 'length');
        // Conditionally launch add field height.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('size', XMLDB_TYPE_INTEGER, '20', null, null, null, null, 'length');
        // Conditionally launch add field height.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '20', null, null, null, null, 'length');
        // Conditionally launch add field height.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '20', null, null, null, null, 'length');
        // Conditionally launch add field height.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Video_directory savepoint reached.
        upgrade_plugin_savepoint(true, 2017040403, 'local', 'video_directory');
    }

    if ($oldversion < 2017043005) {
        $table = new xmldb_table('local_video_directory');
        $field = new xmldb_field('views', XMLDB_TYPE_INTEGER, '13', 0, XMLDB_NOTNULL, null, 0, 'length');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Video_directory savepoint reached.
        upgrade_plugin_savepoint(true, 2017043005, 'local', 'video_directory');
    }

    if ($oldversion < 2017050400) {
        $table = new xmldb_table('local_video_directory');
        $field = new xmldb_field('subs', XMLDB_TYPE_INTEGER, '1', 0, XMLDB_NOTNULL, null, 0, 'length');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Video_directory savepoint reached.
        upgrade_plugin_savepoint(true, 2017050400, 'local', 'video_directory');
    }

    if ($oldversion < 2017062200) {
        // Define table local_video_vers to be created.
        $table = new xmldb_table('local_video_directory_vers');

        // Adding fields to table local_video_directory_vers.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('file_id', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('filename', XMLDB_TYPE_CHAR, '40', null, XMLDB_NOTNULL, null, null);
        $table->add_field('datecreated', XMLDB_TYPE_INTEGER, '20', null, null, null, null);

        // Adding keys to table local_video_directory_multi.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Conditionally launch create table for local_video_directory_multi.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        upgrade_plugin_savepoint(true, 2017062200, 'local', 'video_directory');
    }

    if ($oldversion < 2018060601) {
        $table = new xmldb_table('local_video_directory');
        $field = new xmldb_field('uniqid', XMLDB_TYPE_CHAR, '23', 0, XMLDB_NOTNULL, null, 0, 'views');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Video_directory savepoint reached.
        upgrade_plugin_savepoint(true, 2018060601, 'local', 'video_directory');

        // Fill table with uniqids.
        $videos = $DB->get_records('local_video_directory', array());
        foreach ($videos as $video) {
            $uniqid = uniqid('', true);
            $DB->update_record('local_video_directory', array('id' => $video->id, 'uniqid' => $uniqid));
        }
    }

    if ($oldversion < 2018103100) {
        $table = new xmldb_table('local_video_directory_txtsec');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('video_id', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('orderby', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('content', XMLDB_TYPE_TEXT, '5000', null, XMLDB_NOTNULL, null, null);
        $table->add_field('start', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('end', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('datecreated', XMLDB_TYPE_INTEGER, '20', null, null, null, null);

        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        $table = new xmldb_table('local_video_directory_words');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('video_id', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('section_id', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('orderby', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('word', XMLDB_TYPE_CHAR, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('start', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('end', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('datecreated', XMLDB_TYPE_INTEGER, '20', null, null, null, null);

        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
    }

    if ($oldversion < 2018110104) {
        $table = new xmldb_table('local_video_directory_txtq');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('video_id', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('user_id', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('lang', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('state', XMLDB_TYPE_CHAR, '2', null, XMLDB_NOTNULL, null, null);
        $table->add_field('datecreated', XMLDB_TYPE_INTEGER, '20', null, null, null, null);
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
    }

    if ($oldversion < 2018110705) {
        global $CFG;
        require_once($CFG->dirroot . '/lib/accesslib.php');
        $role = $DB->get_record('role', array('shortname' => 'local_video_directory'));
        if (empty($role)) {
            $roleid = create_role('local_video_directory', 'local_video_directory', 'video system role');
            if (is_int($roleid)) {
                $contextsids = array(CONTEXT_SYSTEM);
                set_role_contextlevels($roleid, $contextsids);
                role_change_permission( $roleid, context_system::instance(), 'local/video_directory:video', 1);
            }
        }
        upgrade_plugin_savepoint(true, 2018110705, 'local', 'video_directory');
    }

    if ($oldversion < 2019011200) {
        $table = new xmldb_table('local_video_directory_crop');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('video_id', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('user_id', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('save', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('state', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, null);
        $table->add_field('datecreated', XMLDB_TYPE_INTEGER, '20', null, null, null, null);
        $table->add_field('datemodified', XMLDB_TYPE_INTEGER, '20', null, null, null, null);
        $table->add_field('startx', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('starty', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('endx', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('endy', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        upgrade_plugin_savepoint(true, 2019011200, 'local', 'video_directory');
    }

    if ($oldversion < 2019012700) {
        $table = new xmldb_table('local_video_directory_merge');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('video_id', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('user_id', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('save', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('state', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, null);
        $table->add_field('datecreated', XMLDB_TYPE_INTEGER, '20', null, null, null, null);
        $table->add_field('datemodified', XMLDB_TYPE_INTEGER, '20', null, null, null, null);
        $table->add_field('video_id_small', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('height', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('border', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('fade', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, null);
        $table->add_field('location', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, null);
        $table->add_field('audio', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, null);
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        $table = new xmldb_table('local_video_directory_cut');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('video_id', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('user_id', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('save', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('state', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, null);
        $table->add_field('datecreated', XMLDB_TYPE_INTEGER, '20', null, null, null, null);
        $table->add_field('datemodified', XMLDB_TYPE_INTEGER, '20', null, null, null, null);
        $table->add_field('secbefore', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('secafter', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        $table = new xmldb_table('local_video_directory_cat');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('video_id', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('user_id', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('save', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('state', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, null);
        $table->add_field('datecreated', XMLDB_TYPE_INTEGER, '20', null, null, null, null);
        $table->add_field('datemodified', XMLDB_TYPE_INTEGER, '20', null, null, null, null);
        $table->add_field('video_id_cat', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        upgrade_plugin_savepoint(true, 2019012700, 'local', 'video_directory');
    }

    if ($oldversion < 2019021901) {
        global $CFG;
        require_once($CFG->dirroot . '/lib/accesslib.php');
        $role = $DB->get_record('role', array('shortname' => 'local_video_directory_admin'));
        if (empty($role)) {
            $roleid = create_role('local_video_directory_admin', 'local_video_directory_admin', 'video admin system role');
            if (is_int($roleid)) {
                $contextsids = array(CONTEXT_SYSTEM);
                set_role_contextlevels($roleid, $contextsids);
                role_change_permission( $roleid, context_system::instance(), 'local/video_directory:video', 1);
            }
        }
        upgrade_plugin_savepoint(true, 2019021901, 'local', 'video_directory');
    }

    if ($oldversion < 2019030600) {
        $table = new xmldb_table('local_video_directory_speed');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('video_id', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('user_id', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('save', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('state', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, null);
        $table->add_field('datecreated', XMLDB_TYPE_INTEGER, '20', null, null, null, null);
        $table->add_field('datemodified', XMLDB_TYPE_INTEGER, '20', null, null, null, null);
        $table->add_field('speed', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        upgrade_plugin_savepoint(true, 2019030600, 'local', 'video_directory');
    }

    if ($oldversion < 2019032400) {
        $table = new xmldb_table('local_video_directory');
        $field = new xmldb_field('usergroup', XMLDB_TYPE_CHAR, '100', 0, null, null, 0, 'owner_id');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Video_directory savepoint reached.
        upgrade_plugin_savepoint(true, 2019032400, 'local', 'video_directory');
    }

    if ($oldversion < 2019052500) {
        $table = new xmldb_table('local_video_directory_catvid');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('video_id', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_field('cat_id', XMLDB_TYPE_INTEGER, '20', null, XMLDB_NOTNULL, null, null);
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        $table = new xmldb_table('local_video_directory_cats');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('father_id', XMLDB_TYPE_INTEGER, '20', 0, XMLDB_NOTNULL, null, null);
        $table->add_field('cat_name', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL, null, null);
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        upgrade_plugin_savepoint(true, 2019052500, 'local', 'video_directory');
    }

    if ($oldversion < 2019090300) {

        $table = new xmldb_table('local_video_directory_vers');
        $field = new xmldb_field('filename', XMLDB_TYPE_CHAR, '70', null, XMLDB_NOTNULL, null, null);
        if ($dbman->field_exists($table, $field)) {
            $dbman->change_field_notnull($table, $field);
        }
        upgrade_plugin_savepoint(true, 2019090300, 'local', 'video_directory');
    }

    if ($oldversion < 2020020900) {
        $table = new xmldb_table('local_video_directory');
        $field = new xmldb_field('description', XMLDB_TYPE_CHAR, '500', null, null, null, null, 'uniqid');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        upgrade_plugin_savepoint(true, 2020020900, 'local', 'video_directory');
    }

    if ($oldversion < 2020140501) {
        $table = new xmldb_table('local_video_directory_zoom');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('zoom_meeting_id', XMLDB_TYPE_INTEGER, '10', 0, XMLDB_NOTNULL, null, null);
        $table->add_field('video_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('video_original_name', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        $field = new xmldb_field('video_original_name', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        upgrade_plugin_savepoint(true, 2020140501, 'local', 'video_directory');
    }

    if ($oldversion < 2020140504) {
        $table = new xmldb_table('local_video_directory_cut');
        $field = new xmldb_field('cuttype', XMLDB_TYPE_CHAR, '20', null, XMLDB_NOTNULL, null, null);
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        upgrade_plugin_savepoint(true, 2020140504, 'local', 'video_directory');
    }

    if ($oldversion < 2020140507) {

        $table = new xmldb_table('local_video_directory');
        $field = new xmldb_field('deletiondate', XMLDB_TYPE_CHAR, '13', null, null, null, null);
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        upgrade_plugin_savepoint(true, 2020140507, 'local', 'video_directory');
    }

    if ($oldversion < 2020140510) {

        $table = new xmldb_table('local_video_directory');
        $field = new xmldb_field('orig_filename', XMLDB_TYPE_CHAR, '1333', null, XMLDB_NOTNULL, null, null);
        if ($dbman->field_exists($table, $field)) {
            $dbman->change_field_type($table, $field);
        }

        $table = new xmldb_table('local_video_directory_zoom');
        $field = new xmldb_field('video_original_name', XMLDB_TYPE_CHAR, '1333', null, null, null, null);
        if ($dbman->field_exists($table, $field)) {
            $dbman->change_field_type($table, $field);
        }
        upgrade_plugin_savepoint(true, 2020140510, 'local', 'video_directory');
    }

    if ($oldversion < 2021010600) {
        $table = new xmldb_table('zoom_redownload_video');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('video_original_name', XMLDB_TYPE_CHAR, '1333', null, null, null, null);
        $table->add_field('video_id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('counter', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        upgrade_plugin_savepoint(true, 2021010600, 'local', 'video_directory');
    }

    if ($oldversion < 2021010604) {

        $table = new xmldb_table('zoom_redownload_video');
        $field = new xmldb_field('video_id', XMLDB_TYPE_CHAR, '1333', null, null, null, null, null, 0);
        if ($dbman->field_exists($table, $field)) {
            $dbman->change_field_default($table, $field);
            $dbman->rename_field($table, $field, 'video_old_id');

        }
        upgrade_plugin_savepoint(true, 2021010604, 'local', 'video_directory');
    }

    return 1;
}
