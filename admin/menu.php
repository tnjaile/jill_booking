<?php
$adminmenu = [];
$i = 1;
$icon_dir = substr(XOOPS_VERSION, 6, 3) == '2.6' ? '' : 'images/admin/';

$adminmenu[$i]['title'] = _MI_TAD_ADMIN_HOME;
$adminmenu[$i]['link'] = 'admin/index.php';
$adminmenu[$i]['desc'] = _MI_TAD_ADMIN_HOME_DESC;
$adminmenu[$i]['icon'] = 'images/admin/home.png';

$i++;
$adminmenu[$i]['title'] = _MI_JILLBOOKIN_ADMENU1;
$adminmenu[$i]['link'] = 'admin/main.php';
$adminmenu[$i]['desc'] = _MI_JILLBOOKIN_ADMENU1_DESC;
$adminmenu[$i]['icon'] = "{$icon_dir}check.png";

// for 預約助手
$i++;
$adminmenu[$i]['title'] = _MI_BOOKING_HELPER_ADMENU1;
$adminmenu[$i]['link'] = 'admin/booking_helper.php';
$adminmenu[$i]['desc'] = _MI_BOOKING_HELPER_ADMENU1_DESC;
$adminmenu[$i]['icon'] = 'images/admin/button.png';

$i++;
$adminmenu[$i]['title'] = _MI_TAD_ADMIN_ABOUT;
$adminmenu[$i]['link'] = 'admin/about.php';
$adminmenu[$i]['desc'] = _MI_TAD_ADMIN_ABOUT_DESC;
$adminmenu[$i]['icon'] = 'images/admin/about.png';
