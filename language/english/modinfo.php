<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tnjaile 製作
// 製作日期：2015-01-23
// $Id:$
// ------------------------------------------------------------------------- //
include_once XOOPS_ROOT_PATH . "/modules/tadtools/language/{$xoopsConfig['language']}/modinfo_common.php";

define('_MI_JILLBOOKIN_NAME', 'Venue Management');
define('_MI_JILLBOOKIN_AUTHOR', 'Jillian');
define('_MI_JILLBOOKIN_CREDITS', '');
define('_MI_JILLBOOKIN_DESC', 'Venue Booking module');
define('_MI_JILLBOOKIN_AUTHOR_WEB', 'Jillian\'s Website');
define('_MI_JILLBOOKIN_ADMENU1', 'Venue Management');
define('_MI_JILLBOOKIN_ADMENU1_DESC', 'Venue Management');
define('_MI_JILLBOOKIN_SMNAME2', 'Batch reservation');
define('_MI_JILLBOOKIN_SMNAME3', 'Reservation list');
// Preferences
define('_MI_JILLBOOKIN_BOOKING_GROUP', 'Who can book a venue');
define('_MI_JILLBOOKIN_BOOKING_GROUP_DESC', 'Select groups that can make venue bookings');
define('_MI_JILLBOOKIN_CAN_CANCEL', 'Cancel reservation site by others');
define('_MI_JILLBOOKIN_CAN_CANCEL_DESC', 'Select groups that can cancel venue bookings done by others');

//1.1 (mamba)

// The name of this module
define('_MI_JILLBOOKIN_DIRNAME', basename(dirname(dirname(__DIR__))));
define('_MI_JILLBOOKIN_HELP_HEADER', __DIR__ . '/help/helpheader.html');
define('_MI_JILLBOOKIN_BACK_2_ADMIN', 'Back to Administration of ');

//help
define('_MI_JILLBOOKIN_HELP_OVERVIEW', 'Overview');
