<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tnjaile 製作
// 製作日期：2015-01-23
// $Id:$
// ------------------------------------------------------------------------- //

$modversion = array();

//---模組基本資訊---//
$modversion['name'] = _MI_JILLBOOKIN_NAME;
$modversion['version']  = '1.0';
$modversion['description'] = _MI_JILLBOOKIN_DESC;
$modversion['author'] = _MI_JILLBOOKIN_AUTHOR;
$modversion['credits']  = _MI_JILLBOOKIN_CREDITS;
$modversion['help'] = 'page=help';
$modversion['license']    = 'GPL see LICENSE';
$modversion['image']    = "images/logo.png";
$modversion['dirname'] = basename(dirname(__FILE__));


//---模組狀態資訊---//
$modversion['status_version'] = '1.0';
$modversion['release_date'] = '2015-04-15';
$modversion['module_website_url'] = '';
$modversion['module_website_name'] = _MI_JILLBOOKIN_AUTHOR_WEB;
$modversion['module_status'] = 'release';
$modversion['author_website_url'] = '';
$modversion['author_website_name'] = _MI_JILLBOOKIN_AUTHOR_WEB;
$modversion['min_php']= 5.2;
$modversion['min_xoops']='2.5';


//---paypal資訊---//
$modversion ['paypal'] = array();
$modversion ['paypal']['business'] = 'tnjaile@gmail.com';
$modversion ['paypal']['item_name'] = 'Donation :'. _MI_JILLBOOKIN_AUTHOR;
$modversion ['paypal']['amount'] = 0;
$modversion ['paypal']['currency_code'] = 'USD';

//---安裝設定---//
$modversion['onInstall'] = "include/onInstall.php";
$modversion['onUpdate'] = "include/onUpdate.php";
$modversion['onUninstall'] = "include/onUninstall.php";

//---啟動後台管理界面選單---//
$modversion['system_menu'] = 1;

//---資料表架構---//
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][1] = "jill_booking";
$modversion['tables'][2] = "jill_booking_date";
$modversion['tables'][3] = "jill_booking_item";
$modversion['tables'][4] = "jill_booking_time";
$modversion['tables'][5] = "jill_booking_week";


//---管理介面設定---//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/main.php";
$modversion['adminmenu'] = "admin/menu.php";

//---使用者主選單設定---//
$modversion['hasMain'] = 1;
$modversion['sub'][2]['name'] =_MI_JILLBOOKIN_SMNAME2;
$modversion['sub'][2]['url'] = "batch.php";
$modversion['sub'][3]['name'] =_MI_JILLBOOKIN_SMNAME3;
$modversion['sub'][3]['url'] = "list.php";
$i=0;
$modversion['templates'][$i]['file'] = 'jill_booking_adm_main.html';
$modversion['templates'][$i]['description'] = 'jill_booking_adm_main.html';

$i++;
$modversion['templates'][$i]['file'] = 'jill_booking_adm_main_b3.html';
$modversion['templates'][$i]['description'] = 'jill_booking_adm_main_b3.html for bootstrap3';

$i++;
$modversion['templates'][$i]['file'] = 'jill_booking_adm_time.html';
$modversion['templates'][$i]['description'] = 'jill_booking_adm_time.html';

$i++;
$modversion['templates'][$i]['file'] = 'jill_booking_adm_time_b3.html';
$modversion['templates'][$i]['description'] = 'jill_booking_adm_time_b3.html';

$i++;
$modversion['templates'][$i]['file'] = 'jill_booking_index.html';
$modversion['templates'][$i]['description'] = 'jill_booking_index.html';

$i++;
$modversion['templates'][$i]['file'] = 'jill_booking_index_b3.html';
$modversion['templates'][$i]['description'] = 'jill_booking_index_b3.html';

$i++;
$modversion['templates'][$i]['file'] = 'jill_booking_batch.html';
$modversion['templates'][$i]['description'] = 'jill_booking_batch.html';

$i++;
$modversion['templates'][$i]['file'] = 'jill_booking_batch_b3.html';
$modversion['templates'][$i]['description'] = 'jill_booking_batch_b3.html';

$i++;
$modversion['templates'][$i]['file'] = 'jill_booking_list.html';
$modversion['templates'][$i]['description'] = 'jill_booking_list.html';

$i++;
$modversion['templates'][$i]['file'] = 'jill_booking_list_b3.html';
$modversion['templates'][$i]['description'] = 'jill_booking_list_b3.html';
//---區塊設定---//


//偏好設定//
$i=0;
$modversion['config'][$i]['name'] = 'booking_group';
$modversion['config'][$i]['title'] = '_MI_JILLBOOKIN_BOOKING_GROUP';
$modversion['config'][$i]['description'] = '_MI_JILLBOOKIN_BOOKING_GROUP_DESC';
$modversion['config'][$i]['formtype']  = 'group_multi';
$modversion['config'][$i]['valuetype'] = 'array';
$modversion['config'][$i]['default'] = '1';
++$i;
$modversion['config'][$i]['name'] = 'can_cancel';
$modversion['config'][$i]['title'] = '_MI_JILLBOOKIN_CAN_CANCEL';
$modversion['config'][$i]['description'] = '_MI_JILLBOOKIN_CAN_CANCEL_DESC';
$modversion['config'][$i]['formtype']  = 'group_multi';
$modversion['config'][$i]['valuetype'] = 'array';
$modversion['config'][$i]['default'] = '1';
?>