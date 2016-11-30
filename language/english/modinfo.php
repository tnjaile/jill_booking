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
define("_MI_JILLBOOKIN_SMNAME4", "Approval list");

// Preferences
define('_MI_JILLBOOKIN_BOOKING_GROUP', 'Who can book a venue');
define('_MI_JILLBOOKIN_BOOKING_GROUP_DESC', 'Select groups that can make venue bookings');
define('_MI_JILLBOOKIN_MAX_BOOKINGWEEK', 'Limit a maximum number of weeks of reservation');
define('_MI_JILLBOOKIN_MAX_BOOKINGWEEK_DESC', 'Limited number of weeks can be booked up to the venue。Please fill number! For example, to limit the upper of five weeks, please fill: 5');
//blocks
define("_MI_JB_BNAME1", "Today's Booking");
define("_MI_JB_BDESC1", "Today's Booking(jb_b_today_list)");
define('_MI_JB_BOPT0_DEF', 'Collapse tab');
define('_MI_JB_BNAME2', "This week's Booking");
define('_MI_JB_BDESC2', "This week's Booking (jill_booking_week_list)");

//1.1 (mamba)

// The name of this module
define('_MI_JILLBOOKIN_DIRNAME', basename(dirname(dirname(__DIR__))));
define('_MI_JILLBOOKIN_HELP_HEADER', __DIR__ . '/help/helpheader.html');
define('_MI_JILLBOOKIN_BACK_2_ADMIN', 'Back to Administration of ');

//help
define('_MI_JILLBOOKIN_HELP_OVERVIEW', 'Overview');
