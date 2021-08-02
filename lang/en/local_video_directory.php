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
 * You may localized strings in your plugin
 *
 * @package    local_video
 * @copyright  2016 OpenApp
 * @license    http://www.gnu.org/copyleft/gpl.html gnu gpl v3 or later
 */

$string['pluginname'] = 'Video Directory';
$string['video_directory:video'] = 'use local video directory';
$string['actions'] = 'Actions';
$string['agree'] = 'Agree to proceed';
$string['are_you_sure'] = 'Are you sure you want to delete this video ?';
$string['are_you_sure_subs'] = 'Are you sure you want to delete this subtitle file of ';
$string['awaitingconversion'] = 'Awaiting conversion';
$string['choose'] = 'Choose';
$string['choose_thumb'] = 'Please select thumbnail after loading is complete';
$string['clicktochangethumb'] = 'Click here to change thumbnail';
$string['close'] = 'Close';
$string['convert_status'] = 'State';
$string['download_status'] = 'Download status';
$string['edit'] = 'Edit video details';
$string['errorcreatingthumbat'] = 'Error creating thumb at';
$string['existing_tags'] = 'Existing tags';
$string['filename'] = 'Filename';
$string['files'] = 'File list';
$string['file_uploaded'] = 'File uploaded succesfully';
$string['freedisk'] = 'Free disk space';
$string['id'] = 'ID';
$string['length'] = 'Video legnth';
$string['list'] = 'List of videos';
$string['live'] = 'Live video';
$string['mass'] = 'Uploaded files';
$string['noimage'] = 'No image';
$string['orig_filename'] = 'Name';
$string['owner'] = 'Owner';
$string['name'] = 'Owner';
$string['play'] = 'Play';
$string['player'] = 'Player view';
$string['private'] = 'Private';
$string['reload'] = 'Reload';
$string['selected_tags'] = 'Selected tags';
$string['show_all'] = 'Show all videos';
$string['size'] = 'File size';
$string['state_1'] = 'File uploaded';
$string['state_2'] = 'File in conversion';
$string['state_3'] = 'File is ready';
$string['state_4'] = 'Waiting for conversion';
$string['state_5'] = 'Conversion failed';
$string['state_6'] = 'Creating multi resolution';
$string['state_7'] = 'Ready + Multi resolution';
$string['state_8'] = 'Downloading the file';
$string['streaming_url'] = 'Streaming URL';
$string['tagarea_local_video_directory'] = 'Videos';
$string['tags'] = 'Tags';
$string['thumb'] = 'Thumbnail';
$string['upload'] = 'Upload';
$string['url_download'] = 'Insert here URL for downloading video';
$string['wget'] = 'Upload from link';
$string['wget_0'] = 'In queue';
$string['wget_1'] = 'Downloading...';
$string['wget_2'] = 'Moved to uploaded files area';
$string['clicktochangethumb'] = 'Click to Change thumb';
$string['ffmpegdrive'] = 'Ffmpeg drive';
$string['ffmpegpath'] = 'Ffmpeg path';
$string['ffprobedrive'] = 'Ffprobe drive';
$string['ffprobepath'] = 'Ffprobe path';
$string['phpdrive'] = 'PHP drive';
$string['phppath'] = 'PHP path';
$string['streamingurl'] = 'Streaming server URL';
$string['ffmpegparameters'] = 'Ffmpeg parameters';
$string['thumbnailseconds'] = 'Thumbnail seconds';
$string['alertdiskspace'] = 'Alert on low free disk space (MBytes)';
$string['cohortallowed'] = 'Cohort ID of allowed users (not relevant anymore)';
$string['clicktochangethumb'] = 'Click to change thumb';
$string['clicktochangethumbdesc'] = 'Click to change thumb';
$string['ffmpegdrivedesc'] = 'If your ffmpeg is not in the same drive as your moodle and not in the system path, please enter the drive letter here.';
$string['ffmpegpathdesc'] = 'Please enter the path to your local ffmpeg executable files including the executable filename itself. Windows users note: please use forward slashes instead of backslashes.';
$string['ffprobedrivedesc'] = 'If your ffprobe is not in the same drive as your moodle and not in the system path, please enter the drive letter here.';
$string['ffprobepathdesc'] = 'Please enter the path to your local ffprobe executable file including the executable filename itself. Windows users note: backslashes are converted to forward slashes.';
$string['phpdrivedesc'] = 'If your php installation is not in the same drive as your moodle and not in the system path, please enter the drive letter here.';
$string['phppathdesc'] = 'Please enter the path to your local php executable file including the executable filename itself. Windows users note: please use forward slashes instead of backslashes.';
$string['xampplink'] = ' If you are using XAMPP click here: <a onclick="document.getElementById(\'id_s_local_video_directory_php\').value = \'/xampp/php/php\'" style="cursor: pointer">Set Value</a>.';
$string['streamingurldesc'] = 'Please enter your streaming server URL including path here';
$string['ffmpegparametersdesc'] = 'For advanced users - Ffmpeg conversion parameters';
$string['thumbnailsecondsdesc'] = 'How many seconds from video start to extract default thumbnail';
$string['alertdiskspacedesc'] = 'Show the free disk space in red (MBytes)';
$string['cohortalloweddesc'] = 'Cohort ID of allowed users';
$string['cohortallowed'] = 'You can create a cohort for users allowed to manage and upload videos, and set its ID here';
$string['multiresolution'] = 'Encode in multiple resolutions';
$string['multiresolutiondesc'] = 'This is important for multi bit rate streaming using Nginx kaltura streaming module';
$string['resolutions'] = 'Resolution to encode';
$string['resolutionsdesc'] = 'Please insert list of resolutions (height) comma separated';
$string['upload_subs'] = 'Subtitles upload';
$string['subs_exist_in_size'] = 'Subtitles file exist and is in size :';
$string['no_file'] = 'File was not uploaded yet';
$string['upload_new_version'] = 'Upload new version';
$string['list_versions'] = 'List versions';
$string['versions'] = 'Versions';
$string['subs_deleted'] = 'Subtitle file deleted successfully.';
$string['restore_in_queue'] = "Restore request in queue...";
$string['cant_upload_or_restore_while_converting'] = "Can't upload or restore while this video is in converting action";
$string['portal'] = "Video Portal";
$string['sure_restore'] = "Are you sure that you want to restore the video from";
$string['restore'] = "Restore";
$string['dashbaseurl'] = "Dash server base url";
$string['dashbaseurldesc'] = "Insert here the base url of your dash server";
$string['allowanonymousembed'] = "Allow anonymous embed";
$string['allowanonymousembeddesc'] = "Allow embedding video without need of Moodle authentication";
$string['nginxmultiuridesc'] = "String for vod_multi_uri_suffix setting in Nginx";
$string['nginxmultiuri'] = "Multiuri nginx setting";
$string['embed'] = "Code for embeding video";
$string['embed_type'] = "Embed Type";
$string['direct'] = "Direct";
$string['authenticated'] = "Authenticated";
$string['embeding'] = "Embed";
$string['hlsbaseurldesc'] = "Insert here your HLS streaming base URL";
$string['hlsbaseurl'] = "HLS streaming base URL";
$string['googlespeech'] = "Enable Google Speech to Text API";
$string['googlespeechdesc'] = "Enabling this let you use google services for searching inside your videos";
$string['googlejson'] = "Google JSON auth";
$string['googlejsondesc'] = "Copy and paste here your Google JSON auth";
$string['googlestoragebucket'] = "Default bucket name in Google cloud storage";
$string['googlestoragebucketdesc'] = "You must create thsi bucket before using the speech recognition service";
$string['fulltext'] = 'Get Text from Video';
$string['textstate_0'] = 'Queued';
$string['textstate_1'] = 'Working on it...';
$string['textstate_2'] = 'Done';
$string['playthisword'] = 'Play this word';
$string['youtube-dlpath'] = 'youtube-dl path';
$string['youtube-dldesc'] = 'If you want to direct download from youtube, install youtube-dl on your server. ( https://yt-dl.org)';
$string['installyoutubedl'] = 'Downloads from youtube works only if youtube-dl is installed and well configured in plugin settings';
$string['showwhere'] = 'Show where is this video used.';
$string['showqr'] = 'Show link to QR code of video.';
$string['showembed'] = 'Show embed code.';
$string['showwheredesc'] = 'This settings allow you to know where is this video used on this moodle site using mod_videostream.';
$string['showqrdesc'] = 'This settings allow you to have a link to a QR code with direct link to the video.';
$string['showembeddesc'] = 'This settings allow you to decide weather to have embed code for embeding video in other sites.';
$string['crop'] = "Crop Video";
$string['studio'] = "Video Editing Studio";
$string['merge'] = "Merge two videos into one";
$string['cut'] = "Cut video";
$string['cutsides'] = "remove video sides";
$string['cutmiddlepart'] = "remove middlepart";
$string['cutfrom'] = "choose start point";
$string['cutto'] = "choose end point";
$string['copyvideovalue'] = "copy video value";
$string['cuttingrange'] = "cutting range:";
$string['cuttype'] = "cut type";
$string['cuttype_help'] =  "remove video sides: removing the two outer parts" . "<br>"
 ."remove middle part: removing the middle part and connecting the two outer parts";
$string['bg_movie'] = "Background Video";
$string['small_movie'] = "Small Video";
$string['border'] = "Border";
$string['height'] = "Height";
$string['location'] = "Location";
$string['right'] = "Right";
$string['left'] = "Left";
$string['audio'] = "audio";
$string['fade_after'] = "Fade After";
$string['fade'] = "Fade";
$string['last_frame'] = "Last Frame";
$string['before'] = "Before";
$string['after'] = "After";
$string['inqueue'] = "Your request is in work...";
$string['newversion'] = "New version of this video";
$string['newvideo'] = "New video";
$string['embedcolumndesc'] = "Show Embed Column in List Table";
$string['embedcolumn'] = "Show Embed Column";
$string['cat'] = "Concatenate Videos";
$string['first'] = "First Video";
$string['second'] = "Second Video";
$string['speed'] = "Video Speed";
$string['group'] = "Group";
$string['usergroup'] = "Group";
$string['customgroup'] = "Custom Group List";
$string['customgroupdesc'] = "Enter here custom group list separated by comma";
$string['fieldorder'] = "View and order of field to view on video list (comma separated)";
$string['settings'] = 'Video Directory Settings';
$string['timecreated'] = 'Upload Time';
$string['allowxmlexport'] = 'Allow XML export of video list';
$string['allowxmlexportdesc'] = 'If you will allow this, the list will be available at ';
$string['groupcloud'] = 'Show cloud of groups near tags';
$string['groupclouddesc'] = 'If this option is set there will be a cloud of groups near tags';
$string['selected_groups'] = 'Selected Groups';
$string['categories'] = 'Categories';
$string['categoriesdesc'] = 'Enable the categories setting to videos';
$string['catscloud'] = 'Show cloud of categories';
$string['catsclouddesc'] = 'If this option is set, there will be a cloud of categories';
$string['selected_cats'] = 'Selected Categories';
$string['manage_cats'] = 'Manage Categories';
$string['father'] = 'Father';
$string['new_category'] = "New category";
$string['views'] = 'Views';
$string['multigroup'] = 'Multi groups for each video';
$string['multigroupdesc'] = 'While this option is enabled, you could set multiple groups to each video';
$string['inuse'] = 'In Use';
$string['nostreaming'] = 'Your streaming server is not set - You probably have to do this command :';
$string['portalips'] = 'Comma separated list of IPs that can get the portal without authentication.';
$string['fulltextedit'] = 'Edit subtitles';
$string['result_content'] = 'Found in text content';
$string['portalimagesbeforesearch'] = 'Show images of video in portal before search';
$string['portalimagesbeforesearchdesc'] = 'This option set whether images of videos will be shown before any search in portal';
$string['description'] = "Description";
$string['enrolteachers'] = "add all teachers to local_video_directory role";
$string['zoomvideosname'] = "pull all the meetings recordings from zoom to the video directory";

$string['zoomApiKey'] = 'Zoom Api Key';
$string['zoomApiKeydesc'] = 'Insert the key of your zoom Api';
$string['zoomApiSecret'] = 'Zoom Api Secret';
$string['zoomApiSecretdesc'] = 'Insert the secret of your zoom Api';
$string['zoomApiUrl'] = 'Zoom Api url';
$string['zoomApiUrldesc'] = 'Insert the url of your zoom Api';
$string['numofzoomusers'] = 'Number of zoom accounts';
$string['numofzoomusersdesc'] = 'Insert the number of zoom accounts';
$string['monthpull'] = 'Month to pull';
$string['monthpulldesc'] = 'Insert the month tou want to pull from. (If not set, current month run)';

$string['deletionrange'] = 'Deletion Range Zoom Videos';
$string['deletionrangedesc'] = 'Num of months to delete zoom videos from video directiry';
$string['deletiontask'] = "Delete zoom videos from video directiry";
$string['active'] = "active";
$string['deletiondate'] = 'Deletion Date';
$string['zoomvideosettings'] = 'Zoom video settings';
$string['zoomvideosettingsdesc'] = 'Settings for pulling zoom videos';
$string['orphanvideoowner'] = 'Userid to orphan videos';
$string['orphanvideoownerdesc'] = 'videos for no owner has been assigned will belong to this user';
$string['disableversion'] = 'Disable versions in video studio';
$string['disableversiondesc'] = 'in video studio disable save the edited video as new version';

$string['sendemailwhenready'] = "send an email to owner when the video is ready to use";
$string['sendemailwhenreadydesc'] = "if checked - moodle will send an email to the owner when the video is ready to use.";
$string['emailsubject'] = 'The zoom video is ready in moodle';
$string['emailmsg'] = 'Hello. the zoom videoid ready in moodle. 
you can find it in the video directory. video id: ';
$string['minimumtimefromzoom'] = 'Minimum minutes to download moovie from zoom';
$string['minimumtimefromzoomdesc'] = 'Insert the minimum minutes to download moovie from zoom. by default 0';
$string['wgeturl'] = "Wget base url";
$string['wgeturldesc'] = "Insert here the base url of the wget";
$string['hlsingle_base_url'] = 'Streaming address for HLS single file';
$string['redownload_from_zoom'] = 'Delete & Redownload error videos from zoom';

$string['metadata_cut'] = 'Cut video from second {$a->secbefore} to second {$a->secafter}';
$string['metadata_cat'] = 'Concat this video to another video with id: {$a->video_id_cat}';
$string['metadata_crop'] = 'Crop the video. X Coordinates: ({$a->startx}, {$a->endx}), Y Coordinates: ({$a->starty}, {$a->endy})';
$string['metadata_speed'] = 'Speeder this video: {$a->speed}%';


$string['disableprivate'] = 'Open the option to make a video public';
$string['disableprivatedesc'] = "Prevent video directory's users to make a video public ";

$string['enrolallteachers'] = 'Open the option to make a video public';
$string['enrolallteachersdesc'] = "Prevent video directory's users to make a video public ";
$string['streamingcheck'] = 'Streaming check';
$string['streamingcheckdesc'] = "streaming check in check_streaming_server_url function";
$string['eventvideo_deleted'] = 'video deleted from video directory';

// Cloud.
$string['settingscloud'] = 'Cloud Settings';
$string['cloudaccesskey'] = 'Cloud access key';
$string['cloudaccesstoken'] = 'Cloud access token';
$string['cloudaccesstokendesc'] = 'Insert the cloud access token';
$string['cloudaccesskeydesc'] = 'Insert the cloud access key';
$string['cloudaccesssecret'] = 'Cloud secret key';
$string['cloudaccesssecretdesc'] = 'Insert the cloud secret key';
$string['cloudvideobucket'] = 'Cloud video bucket';
$string['cloudvideobucketdesc'] = 'Insert the cloud video bucket';
$string['cloudendpoint'] = 'Cloud Link';
$string['cloudendpointdesc'] = 'Insert the cloud main link';
$string['cloudtype'] = 'Cloud Name';
$string['cloudtypedesc'] = 'Insert your Cloud Name (Azure, Google etc...)';
$string['cloudregion'] = 'Cloud region';
$string['cloudregiondesc'] = 'Insert your Cloud region';
$string['storagecloud'] = 'Cloud storage';
$string['storageclouddesc'] = 'Enable storage videos to cloud';
$string['updatevimeoversion'] = 'To replace version  ' . '<a href="https://tinyurl.com/sham4brj">you can contact support</a>' . ' Attached is the latest video and video details to be updated';
$string['updatevimeothumb'] = 'To replace the video icon ' . '<a href="https://tinyurl.com/sham4brj">you can contact support</a>' . ' attaching the image file and the video details that need to be updated ';
$string['vimeoupdates'] = 'vimeo updates';


