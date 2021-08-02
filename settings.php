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
 * You may have settings in your plugin
 *
 * @package    local_video_directory
 * @copyright  2016 OpenApp http://openapp.co.il
 * @license    http://www.gnu.org/copyleft/gpl.html gnu gpl v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {

    $settings = new admin_settingpage( 'local_video_directory', get_string('settings', 'local_video_directory') );
    $iswin = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

    if ($iswin) {
        $settings->add( new admin_setting_configtext(
            'local_video_directory/ffmpegdrive',
            get_string('ffmpegdrive', 'local_video_directory'),
            get_string('ffmpegdrivedesc', 'local_video_directory'),
            '',
            PARAM_ALPHA
        ));
    }

    $settings->add( new admin_setting_configexecutable(
        'local_video_directory/ffmpeg',
        get_string('ffmpegpath', 'local_video_directory'),
        get_string('ffmpegpathdesc', 'local_video_directory'),
        $iswin ? '/ffmpeg/bin/ffmpeg.exe' : $CFG->dirroot . '/local/video_directory/ffmpeg_static_linux/ffmpeg'
    ));

    if ($iswin) {
        $settings->add( new admin_setting_configtext(
            'local_video_directory/ffprobedrive',
            get_string('ffprobedrive', 'local_video_directory'),
            get_string('ffprobedrivedesc', 'local_video_directory'),
            '',
            PARAM_ALPHA
        ));
    }

    $settings->add( new admin_setting_configexecutable(
        'local_video_directory/ffprobe',
        get_string('ffprobepath', 'local_video_directory'),
        get_string('ffprobepathdesc', 'local_video_directory'),
        $iswin ? '/ffmpeg/bin/ffprobe.exe' : $CFG->dirroot . '/local/video_directory/ffmpeg_static_linux/ffprobe'
    ));

    if ($iswin) {
        $settings->add( new admin_setting_configtext(
            'local_video_directory/phpdrive',
            get_string('phpdrive', 'local_video_directory'),
            get_string('phpdrivedesc', 'local_video_directory'),
            '',
            PARAM_ALPHA
        ));
    }

    $settings->add( new admin_setting_configexecutable(
        'local_video_directory/php',
        get_string('phppath', 'local_video_directory'),
        get_string('phppathdesc', 'local_video_directory') . ($iswin ? get_string('xampplink', 'local_video_directory') : ''),
        $iswin ? '/php/php' : '/usr/bin/php'
    ));

    $settings->add( new admin_setting_configexecutable(
        'local_video_directory/youtubedl',
        get_string('youtube-dlpath', 'local_video_directory'),
        get_string('youtube-dldesc', 'local_video_directory'),
        $iswin ? '/bin/youtube-dl.exe' : '/usr/bin/youtube-dl'
    ));

    $settings->add( new admin_setting_configtext(
        'local_video_directory/streaming',
        get_string('streamingurl', 'local_video_directory'),
        get_string('streamingurldesc', 'local_video_directory'),
        $CFG->wwwroot . '/streaming',
        PARAM_URL

    ));

    $settings->add( new admin_setting_configtext(
        'local_video_directory/thumbnail_seconds',
        get_string('thumbnailseconds', 'local_video_directory'),
        get_string('thumbnailsecondsdesc', 'local_video_directory'),
        '5',
        PARAM_INT
    ));

    $settings->add( new admin_setting_configtext(
        'local_video_directory/df',
        get_string('alertdiskspace', 'local_video_directory'),
        get_string('alertdiskspacedesc', 'local_video_directory'),
        '1000',
        PARAM_INT
    ));

    $settings->add( new admin_setting_configcheckbox(
        'local_video_directory/multiresolution',
        get_string('multiresolution', 'local_video_directory'),
        get_string('multiresolutiondesc', 'local_video_directory'),
        '0'
    ));

    $settings->add( new admin_setting_configtext(
        'local_video_directory/resolutions',
        get_string('resolutions', 'local_video_directory'),
        get_string('resolutionsdesc', 'local_video_directory'),
        '1080,720,648,360,288,144',
        PARAM_TEXT
     ));

    $settings->add( new admin_setting_configcheckbox(
        'local_video_directory/showwhere',
        get_string('showwhere', 'local_video_directory'),
        get_string('showwheredesc', 'local_video_directory'),
        '0'
    ));

    $settings->add( new admin_setting_configcheckbox(
        'local_video_directory/showqr',
        get_string('showqr', 'local_video_directory'),
        get_string('showqrdesc', 'local_video_directory'),
        '0'
    ));

    $settings->add( new admin_setting_configcheckbox(
        'local_video_directory/embedcolumn',
        get_string('embedcolumn', 'local_video_directory'),
        get_string('embedcolumndesc', 'local_video_directory'),
        '0'
    ));

    $settings->add( new admin_setting_configcheckbox(
        'local_video_directory/showembed',
        get_string('showembed', 'local_video_directory'),
        get_string('showembeddesc', 'local_video_directory'),
        '0'
    ));

    // Embed type.
    $settings->add(
        new admin_setting_configselect('local_video_directory/embedtype',
        get_string('embed_type', 'local_video_directory'), '', '', array("none" => "none", "dash" => "dash", "hls" => "hls")));

    $settings->add( new admin_setting_configtext(
        'local_video_directory/embedoptions',
        get_string('embed', 'local_video_directory'),
        '',
        'style="width: 30vw; height: 17vw; max-width: 1280px; max-height: 720px;" frameBorder="0" allowfullscreen="true"'
        .' webkitallowfullscreen="true" mozallowfullscreen="true"',
         PARAM_TEXT
    ));

    $settings->add( new admin_setting_configcheckbox(
        'local_video_directory/allowanonymousembed',
        get_string('allowanonymousembed', 'local_video_directory'),
        get_string('allowanonymousembeddesc', 'local_video_directory'),
        '0'
    ));

    $settings->add( new admin_setting_configcheckbox(
        'local_video_directory/allowxmlexport',
        get_string('allowxmlexport', 'local_video_directory'),
        get_string('allowxmlexportdesc', 'local_video_directory') . ' ' . $CFG->wwwroot . '/local/video_directory/xmlexport.php',
        '0'
    ));

    $settings->add( new admin_setting_configtext(
        'local_video_directory/nginxmultiuri',
        get_string('nginxmultiuri', 'local_video_directory'),
        get_string('nginxmultiuridesc', 'local_video_directory'),
        'multiuri',
        PARAM_TEXT
    ));

    $settings->add( new admin_setting_configtext(
        'local_video_directory/dashbaseurl',
        get_string('dashbaseurl', 'local_video_directory'),
        get_string('dashbaseurldesc', 'local_video_directory'),
        $CFG->wwwroot . '/dash',
        PARAM_URL
    ));

    $settings->add( new admin_setting_configtext(
        'local_video_directory/hlsbaseurl',
        get_string('hlsbaseurl', 'local_video_directory'),
        get_string('hlsbaseurldesc', 'local_video_directory'),
        $CFG->wwwroot . '/hls',
        PARAM_URL
    ));

    // HLSingle base URL.
    $settings->add( new admin_setting_configtext('local_video_directory/hlsingle_base_url',
        get_string('hlsingle_base_url', 'local_video_directory'),
        get_string('hlsingle_base_url', 'local_video_directory'),
        $CFG->wwwroot . '/hlsingle',
        PARAM_RAW
    ));

    $settings->add( new admin_setting_configtext(
        'local_video_directory/wgeturl',
        get_string('wgeturl', 'local_video_directory'),
        get_string('wgeturldesc', 'local_video_directory'),
        '/usr/bin/wget',
        PARAM_URL
    ));

    $settings->add( new admin_setting_configcheckbox(
        'local_video_directory/googlespeech',
        get_string('googlespeech', 'local_video_directory'),
        get_string('googlespeechdesc', 'local_video_directory'),
        '0'
    ));

    $settings->add( new admin_setting_configtextarea(
        'local_video_directory/googlejson',
        get_string('googlejson', 'local_video_directory'),
        get_string('googlejsondesc', 'local_video_directory'),
        '',
        PARAM_RAW
    ));

    $settings->add( new admin_setting_configtext(
        'local_video_directory/googlestoragebucket',
        get_string('googlestoragebucket', 'local_video_directory'),
        get_string('googlestoragebucketdesc', 'local_video_directory'),
        'video',
        PARAM_TEXT
    ));

    $argroup = array("none" => get_string('none', 'moodle'),
            "department" => get_string('department', 'moodle'),
            "institution" => get_string('institution', 'moodle'),
            "custom" => get_string('customgroup', 'local_video_directory'));
    $locals = $DB->get_records('user_info_field', ['datatype' => 'text'], 'name');
    foreach ($locals as $local) {
        $argroup['local_' . $local->shortname] = $local->name;
    }
    $settings->add(
        new admin_setting_configselect('local_video_directory/group',
        get_string('group', 'moodle'), '', '', $argroup
    ));

    $settings->add( new admin_setting_configtext(
        'local_video_directory/customgroup',
        get_string('customgroup', 'local_video_directory'),
        get_string('customgroupdesc', 'local_video_directory'),
        'group1, group2, group3',
        PARAM_TEXT
    ));

    $settings->add( new admin_setting_configcheckbox(
        'local_video_directory/groupcloud',
        get_string('groupcloud', 'local_video_directory'),
        get_string('groupclouddesc', 'local_video_directory'),
        '0'
    ));

    $settings->add( new admin_setting_configcheckbox(
        'local_video_directory/categories',
        get_string('categories', 'local_video_directory'),
        get_string('categoriesdesc', 'local_video_directory'),
        '0'
    ));

    $settings->add( new admin_setting_configcheckbox(
        'local_video_directory/catscloud',
        get_string('catscloud', 'local_video_directory'),
        get_string('catsclouddesc', 'local_video_directory'),
        '0'
    ));

    $settings->add( new admin_setting_configtext(
        'local_video_directory/fieldorder',
        get_string('fieldorder', 'local_video_directory'),
        get_string('fieldorder', 'local_video_directory'),
        'actions, thumb, id, name, orig_filename, length, convert_status, private, tags, streaming_url',
        PARAM_TEXT
    ));

    $settings->add( new admin_setting_configtext(
        'local_video_directory/portalips',
        get_string('portalips', 'local_video_directory'),
        get_string('portalips', 'local_video_directory'),
        '',
        PARAM_TEXT
    ));

    $settings->add( new admin_setting_configcheckbox(
        'local_video_directory/portalimagesbeforesearch',
        get_string('portalimagesbeforesearch', 'local_video_directory'),
        get_string('portalimagesbeforesearchdesc', 'local_video_directory'),
        '0'
    ));

    $settings->add( new admin_setting_configcheckbox(
        'local_video_directory/disableversion',
        get_string('disableversion', 'local_video_directory'),
        get_string('disableversiondesc', 'local_video_directory'),
        '0'
    ));

    $settings->add(
        new admin_setting_configcheckbox('local_video_directory/disableprivate',
        get_string('disableprivate', 'local_video_directory'),
        get_string('disableprivatedesc', 'local_video_directory'),
        '0'
    ));

    $settings->add(
        new admin_setting_configcheckbox('local_video_directory/enrolallteachers',
        get_string('enrolallteachers', 'local_video_directory'),
        get_string('enrolallteachersdesc', 'local_video_directory'),
        '0'
    ));

    $settings->add(
        new admin_setting_configcheckbox('local_video_directory/streamingcheck',
        get_string('streamingcheck', 'local_video_directory'),
        get_string('streamingcheckdesc', 'local_video_directory'),
        '1'
    ));

    $settings->add(new admin_setting_heading('local_video_directory',
    get_string('zoomvideosettings', 'local_video_directory'),
    get_string('zoomvideosettingsdesc', 'local_video_directory')));

    $settings->add( new admin_setting_configtext(
        'local_video_directory/zoomApiKey',
        get_string('zoomApiKey', 'local_video_directory'),
        get_string('zoomApiKeydesc', 'local_video_directory'),
        ''
    ));

    $settings->add( new admin_setting_configtext(
        'local_video_directory/zoomApiSecret',
        get_string('zoomApiSecret', 'local_video_directory'),
        get_string('zoomApiSecretdesc', 'local_video_directory'),
        ''
    ));

    $settings->add( new admin_setting_configtext(
        'local_video_directory/zoomApiUrl',
        get_string('zoomApiUrl', 'local_video_directory'),
        get_string('zoomApiUrldesc', 'local_video_directory'),
        'https://api.zoom.us/v2/'
    ));

    /*
    $settings->add( new admin_setting_configtext(
        'local_video_directory/numofzoomusers',
        get_string('numofzoomusers', 'local_video_directory'),
        get_string('numofzoomusersdesc', 'local_video_directory'),
        '500'
    ));
    */

    $monthes = array("current" => "current", "1" => 1 , "2" => 2, "3" => 3, "4" => 4
    , "5" => 5, "6" => 6, "7" => 7, "8" => 8, "9" => 9, "10" => 10, "11" => 11, "12" => 12);
    $settings->add( new admin_setting_configselect('local_video_directory/monthpull',
        get_string('monthpull', 'local_video_directory'),
        get_string('monthpulldesc', 'local_video_directory'),
        'current', $monthes
    ));

    $settings->add( new admin_setting_configcheckbox(
        'local_video_directory/sendemailwhenready',
        get_string('sendemailwhenready', 'local_video_directory'),
        get_string('sendemailwhenreadydesc', 'local_video_directory'),
        '0'
    ));

    $settings->add( new admin_setting_configtext(
        'local_video_directory/minimumtimefromzoom',
        get_string('minimumtimefromzoom', 'local_video_directory'),
        get_string('minimumtimefromzoomdesc', 'local_video_directory'),
        '0'
    ));

    $settings->add( new admin_setting_configtext(
        'local_video_directory/deletionrange',
        get_string('deletionrange', 'local_video_directory'),
        get_string('deletionrangedesc', 'local_video_directory'),
        '0'
    ));

    $settings->add( new admin_setting_configtext(
        'local_video_directory/deletionrange',
        get_string('deletionrange', 'local_video_directory'),
        get_string('deletionrangedesc', 'local_video_directory'),
        '0'
    ));

    $settings->add( new admin_setting_configtext(
        'local_video_directory/orphanvideoowner',
        get_string('orphanvideoowner', 'local_video_directory'),
        get_string('orphanvideoownerdesc', 'local_video_directory'),
        '0'
    ));

    // Create.
    $ADMIN->add( 'localplugins', $settings );

    $ADMIN->add('server', new admin_externalpage('local_video_directory_list',
        get_string('pluginname', 'local_video_directory'),
        new moodle_url('/local/video_directory/')));


    // Cloud settings.
    $cloudsettings = new admin_settingpage( 'local_video_directory_cloud', get_string('settingscloud', 'local_video_directory') );

    /*
    $cloudsettings->add( new admin_setting_configcheckbox(
        'local_video_directory_cloud/storagecloud',
        get_string('storagecloud', 'local_video_directory'),
        get_string('storageclouddesc', 'local_video_directory'),
        '0'
    ));
    */

    $cloudsettings->add( new admin_setting_configselect(
        'local_video_directory_cloud/cloudtype',
        get_string('cloudtype', 'local_video_directory'),
        get_string('cloudtypedesc', 'local_video_directory'),
        '',
        array("None" => "None", "Azure" => "Azure", "Vimeo" => "Vimeo")
    ));

    $cloudsettings->add( new admin_setting_configtext(
        'local_video_directory_cloud/accesskey',
        get_string('cloudaccesskey', 'local_video_directory'),
        get_string('cloudaccesskeydesc', 'local_video_directory'),
        '0'
    ));
    $cloudsettings->add( new admin_setting_configtext(
        'local_video_directory_cloud/accesssecret',
        get_string('cloudaccesssecret', 'local_video_directory'),
        get_string('cloudaccesssecretdesc', 'local_video_directory'),
        '0'
    ));
    $cloudsettings->add( new admin_setting_configtext(
        'local_video_directory_cloud/accesstoken',
        get_string('cloudaccesstoken', 'local_video_directory'),
        get_string('cloudaccesstokendesc', 'local_video_directory'),
        '0'
    ));
    $cloudsettings->add( new admin_setting_configtext(
        'local_video_directory_cloud/endpoint',
        get_string('cloudendpoint', 'local_video_directory'),
        get_string('cloudendpointdesc', 'local_video_directory'),
        '0'
    ));
    $cloudsettings->add( new admin_setting_configtext(
        'local_video_directory_cloud/videobucket',
        get_string('cloudvideobucket', 'local_video_directory'),
        get_string('cloudvideobucketdesc', 'local_video_directory'),
        '0'
    ));
    
    $cloudsettings->add( new admin_setting_configtext(
        'local_video_directory_cloud/region',
        get_string('cloudregion', 'local_video_directory'),
        get_string('cloudregiondesc', 'local_video_directory'),
        '0'
    ));
    // $cloudsettings->add( new admin_setting_configtext(
    //     'local_video_directory_cloud/cloudetype',
    //     get_string('cloudetype', 'local_video_directory'),
    //     get_string('cloudetypedesc', 'local_video_directory'),
    //     '0'
    // ));
    $ADMIN->add( 'localplugins', $cloudsettings );




}
