<?php
$modversion = array();

//---模組基本資訊---//
$modversion['name'] = _MI_JILLBOOKIN_NAME;
// $modversion['version'] = '2.42';
$modversion['version'] = $_SESSION['xoops_version'] >= 20511 ? '3.0.0-Stable' : '3.0';
$modversion['description'] = _MI_JILLBOOKIN_DESC;
$modversion['author'] = _MI_JILLBOOKIN_AUTHOR;
$modversion['credits'] = _MI_JILLBOOKIN_CREDITS;
$modversion['help'] = 'page=help';
$modversion['license'] = 'GNU GPL 2.0';
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = basename(dirname(__FILE__));

//---模組狀態資訊---//
$modversion['status_version'] = '2.42';
$modversion['release_date'] = '2022-09-02';
$modversion['module_website_url'] = '';
$modversion['module_website_name'] = _MI_JILLBOOKIN_AUTHOR_WEB;
$modversion['module_status'] = 'release';
$modversion['author_website_url'] = '';
$modversion['author_website_name'] = _MI_JILLBOOKIN_AUTHOR_WEB;
$modversion['min_php'] = 5.4;
$modversion['min_xoops'] = '2.5.8';

//---paypal資訊---//
$modversion['paypal'] = array();
$modversion['paypal']['business'] = 'tnjaile@gmail.com';
$modversion['paypal']['item_name'] = 'Donation :' . _MI_JILLBOOKIN_AUTHOR;
$modversion['paypal']['amount'] = 0;
$modversion['paypal']['currency_code'] = 'USD';

//---安裝設定---//
// $modversion['onInstall']   = "include/onInstall.php";
$modversion['onUpdate'] = "include/onUpdate.php";
// $modversion['onUninstall'] = "include/onUninstall.php";

//---啟動後台管理界面選單---//
$modversion['system_menu'] = 1;

//---資料表架構---//
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'] = [
    'jill_booking',
    'jill_booking_date',
    'jill_booking_item',
    'jill_booking_time',
    'jill_booking_week',
];

//---管理介面設定---//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/main.php";
$modversion['adminmenu'] = "admin/menu.php";

//---使用者主選單設定---//
$modversion['hasMain'] = 1;
$modversion['sub'] = [
    ['name' => _MI_JILLBOOKIN_SMNAME2, 'url' => 'batch.php'],
    ['name' => _MI_JILLBOOKIN_SMNAME3, 'url' => 'list.php'],
    ['name' => _MI_JILLBOOKIN_SMNAME4, 'url' => 'listapproval.php'],
];

$templates = [
    'jill_booking_adm_main.tpl',
    'jill_booking_adm_time.tpl',
    'jill_booking_adm_approval.tpl',
    'jill_booking_index.tpl',
    'jill_booking_batch.tpl',
    'jill_booking_list.tpl',
    'jill_booking_listapproval.tpl',
    'booking_helper_adm_main.tpl',
];

foreach ($templates as $file) {
    $modversion['templates'][] = [
        'file' => $file,
        'description' => $file,
    ];
}

$modversion['blocks'] = [
    [
        'file' => 'jb_b_today_list.php',
        'name' => _MI_JB_BNAME1,
        'description' => _MI_JB_BDESC1,
        'show_func' => 'jb_b_today_list',
        'template' => 'jb_b_today_list.tpl',
        'edit_func' => 'jb_b_today_list_edit',
        'options' => [
            '_MB_JB_B_TODAY_LIST_OPT0_VAL0' => 'accordion',
            '_MB_JB_B_TODAY_LIST_OPT0_VAL1' => 'default',
            '_MB_JB_B_TODAY_LIST_OPT0_VAL2' => 'vertical',
        ],
    ],
    [
        'file' => 'jb_b_week_list.php',
        'name' => _MI_JB_BNAME2,
        'description' => _MI_JB_BDESC2,
        'show_func' => 'jb_b_week_list',
        'template' => 'jb_b_week_list.tpl',
        'edit_func' => 'jb_b_week_list_edit',
        'options' => [
            '_MB_JB_B_TODAY_LIST_OPT0_VAL0' => 'accordion',
            '_MB_JB_B_TODAY_LIST_OPT0_VAL1' => 'default',
            '_MB_JB_B_TODAY_LIST_OPT0_VAL2' => 'vertical',
        ],
    ],
];

$modversion['config'] = [
    [
        'name' => 'booking_group',
        'title' => '_MI_JILLBOOKIN_BOOKING_GROUP',
        'description' => '_MI_JILLBOOKIN_BOOKING_GROUP_DESC',
        'formtype' => 'group_multi',
        'valuetype' => 'array',
        'default' => '1',
    ],
    [
        'name' => 'max_bookingweek',
        'title' => '_MI_JILLBOOKIN_MAX_BOOKINGWEEK',
        'description' => '_MI_JILLBOOKIN_MAX_BOOKINGWEEK_DESC',
        'formtype' => 'textbox',
        'valuetype' => 'int',
        'default' => '4',
    ],
    [
        'name' => 'can_send_mail',
        'title' => '_MI_JILLBOOKIN_CAN_SEND_MAIL',
        'description' => '_MI_JILLBOOKIN_CAN_SEND_MAIL_DESC',
        'formtype' => 'yesno',
        'valuetype' => 'int',
        'default' => 1,
    ],
];
