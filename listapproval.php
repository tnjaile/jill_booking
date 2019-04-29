<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tnjaile 製作
// 製作日期：2015-01-23
// $Id
// ------------------------------------------------------------------------- //
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
include "header.php";
$xoopsOption['template_main'] = "jill_booking_listapproval.tpl";
include_once XOOPS_ROOT_PATH . "/header.php";
if (empty($Isapproval)) {
    redirect_header(XOOPS_URL, 3, _MD_JILLBOOKIN_NOAPPROVAL);
}
/*-----------功能函數區--------------*/
//列出所有jill_booking資料
function jill_booking_approvallist($jbi_sn = "")
{
    global $xoopsDB, $xoopsTpl, $xoopsUser;
    $uid  = $xoopsUser->uid();
    $myts = MyTextSanitizer::getInstance();
    //場地設定
    $item_opt = get_jill_booking_time_options($jbi_sn, $uid);
    //die(var_export($item_opt));
    if (!empty($jbi_sn)) {
        $itemArr       = get_jill_booking_item($jbi_sn, 1);
        $checkapproval = explode(";", $itemArr['jbi_approval']);
        if (in_array($uid, $checkapproval)) {
            $sql = "select b.jb_sn,b.jbt_sn,b.jb_date,b.jb_waiting,b.jb_status,c.jbi_sn,c.jbt_title,d.jb_uid,d.jb_booking_time,d.jb_booking_content,d.jb_start_date,d.jb_end_date from  `" . $xoopsDB->prefix("jill_booking_date") . "` as b
            join `" . $xoopsDB->prefix("jill_booking_time") . "` as c on b.jbt_sn=c.jbt_sn
            join `" . $xoopsDB->prefix("jill_booking") . "` as d on b.jb_sn=d.jb_sn
            where c.jbi_sn='{$jbi_sn}' && b.pass_date='0000-00-00' && b.jb_date>= ' " . date("Y-m-d", xoops_getUserTimestamp(time())) . " ' order by b.jb_date ,b.jbt_sn,d.jb_booking_time";

            //die($sql);
            //Utility::Utility::getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
            $PageBar = Utility::getPageBar($sql, 20, 10, null, null);
            $bar     = $PageBar['bar'];
            $sql     = $PageBar['sql'];
            $total   = $PageBar['total'];

            $result = $xoopsDB->query($sql) or Utility::web_error($sql);

            $all_content = array();
            $i           = 0;
            while ($all = $xoopsDB->fetchArray($result)) {
                //以下會產生這些變數： a.jb_sn,a.jb_week,a.jbt_sn,b.jb_date,b.jb_waiting,b.jb_status,c.jbi_sn,c.jbt_title,c.jbt_sort,c.jbt_week,d.jb_uid,d.jb_booking_time,d.jb_booking_content,d.jb_start_date,d.jb_end_date
                foreach ($all as $k => $v) {
                    $$k = $v;
                }

                //過濾讀出的變數值
                $jb_booking_content = $myts->displayTarea($jb_booking_content, 1, 1, 0, 1, 0);
                $jb_start_date      = $myts->htmlSpecialChars($jb_start_date);
                $jb_end_date        = $myts->htmlSpecialChars($jb_end_date);

                $all_content[$i]['jb_sn']              = $jb_sn;
                $all_content[$i]['jbt_sn']             = $jbt_sn;
                $all_content[$i]['jb_date']            = $jb_date;
                $all_content[$i]['jb_week']            = date('w', strtotime($jb_date));
                $all_content[$i]['jb_waiting']         = $jb_waiting;
                $all_content[$i]['jb_status']          = $jb_status;
                $all_content[$i]['jbi_sn']             = $jbi_sn;
                $all_content[$i]['jbt_title']          = $jbt_title;
                $all_content[$i]['jb_uid']             = XoopsUser::getUnameFromId($jb_uid, 1);
                $all_content[$i]['jb_booking_time']    = $jb_booking_time;
                $all_content[$i]['jb_booking_content'] = $jb_booking_content;
                $all_content[$i]['jb_start_date']      = $jb_start_date;
                $all_content[$i]['jb_end_date']        = $jb_end_date;
                $all_content[$i]['had_pass']           = get_had_pass($jbt_sn, $jb_date);
                $i++;
            }
            $xoopsTpl->assign('bar', $bar);
            $xoopsTpl->assign('all_content', $all_content);
        }
    }
    //die(var_dump($all_content));
    //刪除確認的JS
    if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php")) {
        redirect_header("index.php", 3, _MD_NEED_TADTOOLS);
    }
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/sweet_alert.php";
    $sweet_alert              = new sweet_alert();
    $delete_jill_booking_func = $sweet_alert->render('delete_jill_booking_func', "{$_SERVER['PHP_SELF']}?op=delete_booking&jb_info=", "jb_info");
    $xoopsTpl->assign('delete_jill_booking_func', $delete_jill_booking_func);

    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
    $xoopsTpl->assign('item_opt', $item_opt);
    $xoopsTpl->assign('now_op', 'jill_booking_approvallist');

}
//審核通過
function update_jb_status($jb_sn = "", $jb_date = "", $jbt_sn = "", $jbi_sn = "")
{
    global $xoopsDB, $xoopsTpl, $xoopsUser;
    $uid = $xoopsUser->uid();
    //正確抓取XOOPS時間
    $now           = date('Y-m-d', xoops_getUserTimestamp(time()));
    $itemArr       = get_jill_booking_item($jbi_sn, 1);
    $checkapproval = explode(";", $itemArr['jbi_approval']);
    if (in_array($uid, $checkapproval)) {
        //jb_sn, jb_date,jbt_sn, jbi_sn
        $sql = "update `" . $xoopsDB->prefix("jill_booking_date") . "` set
                `jb_status` = '1',
                `approver` = '{$uid}' ,
                `pass_date` = '{$now}'
                where `jb_sn` = '$jb_sn' && `jb_date`='{$jb_date}' && `jbt_sn`='$jbt_sn' ";
        //die($sql);
        $xoopsDB->queryF($sql) or die('0');

    }

    die('1');

}
//取得審核通過者
function get_had_pass($jbt_sn = "", $jb_date = "")
{
    global $xoopsDB;

    $sql = "select a.pass_date,b.jb_uid from  `" . $xoopsDB->prefix("jill_booking_date") . "` as a
            join `" . $xoopsDB->prefix("jill_booking") . "` as b on b.jb_sn=a.jb_sn
            where a.pass_date!='0000-00-00' && a.jb_date='{$jb_date}'  && a.jbt_sn='{$jbt_sn}' && a.jb_waiting='1' order by a.jb_waiting";
    //die($sql);
    $result = $xoopsDB->query($sql) or Utility::web_error($sql);

    list($pass_date, $jb_uid) = $xoopsDB->fetchRow($result);
    //列出預約者資訊
    if (!empty($jb_uid)) {
        $users = $pass_date . _MD_JILLBOOKIN_HADPASS . XoopsUser::getUnameFromId($jb_uid, 1);
    }

    return $users;
}
/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op      = system_CleanVars($_REQUEST, 'op', '', 'string');
$jb_sn   = system_CleanVars($_REQUEST, 'jb_sn', '', 'int');
$jbi_sn  = system_CleanVars($_REQUEST, 'jbi_sn', '', 'int');
$jbt_sn  = system_CleanVars($_REQUEST, 'jbt_sn', '', 'int');
$jb_info = system_CleanVars($_REQUEST, 'jb_info', '', 'string');
switch ($op) {
/*---判斷動作請貼在下方---*/
    case "update_jb_status":
        if (is_date($_REQUEST['jb_date']) == 1) {
            update_jb_status($jb_sn, $_REQUEST['jb_date'], $jbt_sn, $jbi_sn);
        }
        break;

    case "delete_booking":
        $infoArr = explode("_", $jb_info);
        $jbi_sn  = delete_booking($infoArr[1], $infoArr[2], $infoArr[3]);
        header("location: {$_SERVER['PHP_SELF']}?jbi_sn={$jbi_sn}");
        break;

    default:
        jill_booking_approvallist($jbi_sn);
        break;

        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign("toolbar", Utility::toolbar_bootstrap($interface_menu));
$xoopsTpl->assign("isAdmin", $isAdmin);
$xoopsTpl->assign("can_booking", $can_booking);
$xoopsTpl->assign("Isapproval", $Isapproval);
include_once XOOPS_ROOT_PATH . '/footer.php';
