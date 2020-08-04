<?php
$modversion = array();

//---模組基本資訊---//
$modversion['name']        = _MI_JILLBOOKIN_NAME;
$modversion['version']     = '2.34';
$modversion['description'] = _MI_JILLBOOKIN_DESC;
$modversion['author']      = _MI_JILLBOOKIN_AUTHOR;
$modversion['credits']     = _MI_JILLBOOKIN_CREDITS;
$modversion['help']        = 'page=help';
$modversion['license']     = 'GNU GPL 2.0';
$modversion['image']       = "images/logo.png";
$modversion['dirname']     = basename(dirname(__FILE__));

//---模組狀態資訊---//
$modversion['status_version']      = '2.34';
$modversion['release_date']        = '2020-08-04';
$modversion['module_website_url']  = '';
$modversion['module_website_name'] = _MI_JILLBOOKIN_AUTHOR_WEB;
$modversion['module_status']       = 'release';
$modversion['author_website_url']  = '';
$modversion['author_website_name'] = _MI_JILLBOOKIN_AUTHOR_WEB;
$modversion['min_php']             = 5.6;
$modversion['min_xoops']           = '2.5.8';

//---paypal資訊---//
$modversion['paypal']                  = array();
$modversion['paypal']['business']      = 'tnjaile@gmail.com';
$modversion['paypal']['item_name']     = 'Donation :' . _MI_JILLBOOKIN_AUTHOR;
$modversion['paypal']['amount']        = 0;
$modversion['paypal']['currency_code'] = 'USD';

//---安裝設定---//
// $modversion['onInstall']   = "include/onInstall.php";
$modversion['onUpdate'] = "include/onUpdate.php";
// $modversion['onUninstall'] = "include/onUninstall.php";

//---啟動後台管理界面選單---//
$modversion['system_menu'] = 1;

//---資料表架構---//
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][1]        = "jill_booking";
$modversion['tables'][2]        = "jill_booking_date";
$modversion['tables'][3]        = "jill_booking_item";
$modversion['tables'][4]        = "jill_booking_time";
$modversion['tables'][5]        = "jill_booking_week";

//---管理介面設定---//
$modversion['hasAdmin']   = 1;
$modversion['adminindex'] = "admin/main.php";
$modversion['adminmenu']  = "admin/menu.php";

//---使用者主選單設定---//
$modversion['hasMain']                      = 1;
$modversion['sub'][2]['name']               = _MI_JILLBOOKIN_SMNAME2;
$modversion['sub'][2]['url']                = "batch.php";
$modversion['sub'][3]['name']               = _MI_JILLBOOKIN_SMNAME3;
$modversion['sub'][3]['url']                = "list.php";
$modversion['sub'][4]['name']               = _MI_JILLBOOKIN_SMNAME4;
$modversion['sub'][4]['url']                = "listapproval.php";
$i                                          = 0;
$modversion['templates'][$i]['file']        = 'jill_booking_adm_main.tpl';
$modversion['templates'][$i]['description'] = 'jill_booking_adm_main.tpl';

$i++;
$modversion['templates'][$i]['file']        = 'jill_booking_adm_time.tpl';
$modversion['templates'][$i]['description'] = 'jill_booking_adm_time.tpl';

$i++;
$modversion['templates'][$i]['file']        = 'jill_booking_adm_approval.tpl';
$modversion['templates'][$i]['description'] = 'jill_booking_adm_approval.tpl';

$i++;
$modversion['templates'][$i]['file']        = 'jill_booking_index.tpl';
$modversion['templates'][$i]['description'] = 'jill_booking_index.tpl';

$i++;
$modversion['templates'][$i]['file']        = 'jill_booking_batch.tpl';
$modversion['templates'][$i]['description'] = 'jill_booking_batch.tpl';

$i++;
$modversion['templates'][$i]['file']        = 'jill_booking_list.tpl';
$modversion['templates'][$i]['description'] = 'jill_booking_list.tpl';

$i++;
$modversion['templates'][$i]['file']        = 'jill_booking_listapproval.tpl';
$modversion['templates'][$i]['description'] = 'jill_booking_listapproval.tpl';

// for 預約助手
$i++;
$modversion['templates'][$i]['file']        = 'booking_helper_adm_main.tpl';
$modversion['templates'][$i]['description'] = 'booking_helper_adm_main.tpl';

//---區塊設定---//
$i                                       = 0;
$modversion['blocks'][$i]['file']        = "jb_b_today_list.php";
$modversion['blocks'][$i]['name']        = _MI_JB_BNAME1;
$modversion['blocks'][$i]['description'] = _MI_JB_BDESC1;
$modversion['blocks'][$i]['show_func']   = "jb_b_today_list";
$modversion['blocks'][$i]['template']    = "jb_b_today_list.tpl";
$modversion['blocks'][$i]['edit_func']   = 'jb_b_today_list_edit';
$modversion['blocks'][$i]['options']     = array('_MB_JB_B_TODAY_LIST_OPT0_VAL0' => 'accordion', '_MB_JB_B_TODAY_LIST_OPT0_VAL1' => 'default', '_MB_JB_B_TODAY_LIST_OPT0_VAL2' => 'vertical');
$i++;
$modversion['blocks'][$i]['file']        = 'jb_b_week_list.php';
$modversion['blocks'][$i]['name']        = _MI_JB_BNAME2;
$modversion['blocks'][$i]['description'] = _MI_JB_BDESC2;
$modversion['blocks'][$i]['show_func']   = 'jb_b_week_list';
$modversion['blocks'][$i]['template']    = 'jb_b_week_list.tpl';
$modversion['blocks'][$i]['edit_func']   = 'jb_b_week_list_edit';
$modversion['blocks'][$i]['options']     = array('_MB_JB_B_TODAY_LIST_OPT0_VAL0' => 'accordion', '_MB_JB_B_TODAY_LIST_OPT0_VAL1' => 'default', '_MB_JB_B_TODAY_LIST_OPT0_VAL2' => 'vertical');
//偏好設定//
$i                                       = 0;
$modversion['config'][$i]['name']        = 'booking_group';
$modversion['config'][$i]['title']       = '_MI_JILLBOOKIN_BOOKING_GROUP';
$modversion['config'][$i]['description'] = '_MI_JILLBOOKIN_BOOKING_GROUP_DESC';
$modversion['config'][$i]['formtype']    = 'group_multi';
$modversion['config'][$i]['valuetype']   = 'array';
$modversion['config'][$i]['default']     = '1';
++$i;
$modversion['config'][$i]['name']        = 'max_bookingweek';
$modversion['config'][$i]['title']       = '_MI_JILLBOOKIN_MAX_BOOKINGWEEK';
$modversion['config'][$i]['description'] = '_MI_JILLBOOKIN_MAX_BOOKINGWEEK_DESC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = '4';
