<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tnjaile 製作
// 製作日期：2015-01-23
// $Id:$
// ------------------------------------------------------------------------- //

include_once dirname(dirname(__DIR__)) . "/tadtools/language/{$xoopsConfig['language']}/admin_common.php";

define('_TAD_NEED_TADTOOLS', "This module needs TadTools module. You can download TadTools from <a href='http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50' target='_blank'>Tad's web</a>.");


define('_MA_JILLBOOKIN_ADD', 'New Venue');
define('_MA_JILLBOOKIN_ADD_TIME', 'Submit Room');
define('_MA_JILLBOOKIN_JBT_SN', 'Room number');
define('_MA_JILLBOOKIN_JBI_SN', 'Venue Number');
define('_MA_JILLBOOKIN_JBT_TITLE', 'Room Name');
define('_MA_JILLBOOKIN_JBT_SORT', 'Sorting');
define('_MA_JILLBOOKIN_JBT_WEEK', 'Available for Booking on these days');
define('_MA_JILLBOOKIN_JBI_TITLE', 'Venue Name');
define('_MA_JILLBOOKIN_JBI_DESC', 'Venue Description');
define('_MA_JILLBOOKIN_JBI_SORT', 'Venue sorting');
define('_MA_JILLBOOKIN_JBI_ENABLE', 'Enabled');
define('_MA_JILLBOOKIN_JBI_APPROVAL', 'Approved');
define('_MA_JILLBOOKIN_BACK', 'Back to the venue management');
define('_MA_JILLBOOKIN_SETTIME', 'Scheduling');
define('_MA_JILLBOOKIN_COPY', 'Copy');
define('_MA_JILLBOOKIN_COPY_DESC1', 'Or batch copy:');
define('_MA_JILLBOOKIN_COPY_DESC2', 'Period');
define('_MA_JILLBOOKIN_W0', 'Sunday');
define('_MA_JILLBOOKIN_W1', 'Monday');
define('_MA_JILLBOOKIN_W2', 'Tuesday');
define('_MA_JILLBOOKIN_W3', 'Wednesday');
define('_MA_JILLBOOKIN_W4', 'Thursday');
define('_MA_JILLBOOKIN_W5', 'Friday');
define('_MA_JILLBOOKIN_W6', 'Saturday');
define('_MA_JILLBOOKIN_JBI_START', 'Start date');
define('_MA_JILLBOOKIN_JBI_END', 'End date');
define('_MA_JILLBOOKIN_JBI_PERIOD', 'Beginning and ending period');
define('_MA_JILLBOOKIN_IMPORT', 'Fast import from the model set period');
define('_MA_JILLBOOKIN_IMPORT_18', 'Fast import 1-8 set period');
define('_MA_JILLBOOKIN_IMPORT_APM', 'Fast import PM period');
define('_MA_JILLBOOKIN_IMPORT_PLACE', 'Fast import %s set time period (the period of "%s")');
define('_MA_JILLBOOKIN_N_TIME', '"%s" per Day');
define('_MA_JILLBOOKIN_BOOKING_TIME', 'It has "%s" reservations, can not be deleted.');
define('_MA_JILLBOOKIN_AM', 'AM');
define('_MA_JILLBOOKIN_PM', 'PM');
