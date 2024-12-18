<?php
use Xmf\Request;
use XoopsModules\Jill_booking\Tools;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$xoopsOption['template_main'] = "jill_booking_listapproval.tpl";
require_once XOOPS_ROOT_PATH . '/header.php';
if (empty($_SESSION['Isapproval'])) {
    redirect_header(XOOPS_URL, 3, _MD_JILLBOOKIN_NOAPPROVAL);
}
/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$jb_info = Request::getString('jb_info');
$jb_sn = Request::getInt('jb_sn');
$jbt_sn = Request::getInt('jbt_sn');
$jbi_sn = Request::getInt('jbi_sn');

switch ($op) {
/*---判斷動作請貼在下方---*/
    case "update_jb_status":
        if (is_date($_REQUEST['jb_date']) == 1) {
            update_jb_status($jb_sn, $_REQUEST['jb_date'], $jbt_sn, $jbi_sn);
        }
        break;

    case "delete_booking":
        $infoArr = explode("_", $jb_info);
        $jbi_sn = delete_booking($infoArr[1], $infoArr[2], $infoArr[3]);
        header("location: {$_SERVER['PHP_SELF']}?jbi_sn={$jbi_sn}");
        break;

    default:
        jill_booking_approvallist($jbi_sn);
        break;

        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign("toolbar", Utility::toolbar_bootstrap($interface_menu, false, $interface_icon));
$xoopsTpl->assign('jill_book_adm', $jill_book_adm);
$xoTheme->addStylesheet('modules/jill_booking/css/module.css');
include_once XOOPS_ROOT_PATH . '/footer.php';

/*-----------功能函數區--------------*/
//列出所有jill_booking資料
function jill_booking_approvallist($jbi_sn = "")
{
    global $xoopsDB, $xoopsTpl, $xoopsUser;
    $uid = $xoopsUser->uid();
    $myts = \MyTextSanitizer::getInstance();
    //場地設定
    $item_opt = get_jill_booking_time_options($jbi_sn, $uid);
    // Utility::dd($item_opt);
    if (!empty($jbi_sn)) {
        $itemArr = get_jill_booking_item($jbi_sn, 1);
        $checkapproval = explode(",", $itemArr['jbi_approval']);
        if (in_array($uid, $checkapproval)) {
            $sql = "select b.jb_sn,b.jbt_sn,b.jb_date,b.jb_waiting,b.jb_status,c.jbi_sn,c.jbt_title,d.jb_uid,d.jb_booking_time,d.jb_booking_content,d.jb_start_date,d.jb_end_date from  `" . $xoopsDB->prefix("jill_booking_date") . "` as b
            join `" . $xoopsDB->prefix("jill_booking_time") . "` as c on b.jbt_sn=c.jbt_sn
            join `" . $xoopsDB->prefix("jill_booking") . "` as d on b.jb_sn=d.jb_sn
            where c.jbi_sn='{$jbi_sn}' && b.pass_date='0000-00-00' && b.jb_date>= ' " . date("Y-m-d", xoops_getUserTimestamp(time())) . " ' order by b.jb_date ,b.jbt_sn,d.jb_booking_time";

            //die($sql);
            //Utility::Utility::getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
            $PageBar = Utility::getPageBar($sql, 20, 10, null, null);
            $bar = $PageBar['bar'];
            $sql = $PageBar['sql'];
            $total = $PageBar['total'];

            $result = Utility::query($sql);

            $all_content = array();
            $i = 0;
            while ($all = $xoopsDB->fetchArray($result)) {
                //以下會產生這些變數： a.jb_sn,a.jb_week,a.jbt_sn,b.jb_date,b.jb_waiting,b.jb_status,c.jbi_sn,c.jbt_title,c.jbt_sort,c.jbt_week,d.jb_uid,d.jb_booking_time,d.jb_booking_content,d.jb_start_date,d.jb_end_date
                foreach ($all as $k => $v) {
                    $$k = $v;
                }

                //過濾讀出的變數值
                $jb_booking_content = $myts->displayTarea($jb_booking_content, 1, 1, 0, 1, 0);
                $jb_start_date = $myts->htmlSpecialChars($jb_start_date);
                $jb_end_date = $myts->htmlSpecialChars($jb_end_date);

                $all_content[$i]['jb_sn'] = $jb_sn;
                $all_content[$i]['jbt_sn'] = $jbt_sn;
                $all_content[$i]['jb_date'] = $jb_date;
                $all_content[$i]['jb_week'] = date('w', strtotime($jb_date));
                $all_content[$i]['jb_waiting'] = $jb_waiting;
                $all_content[$i]['jb_status'] = $jb_status;
                $all_content[$i]['jbi_sn'] = $jbi_sn;
                $all_content[$i]['jbt_title'] = $jbt_title;
                $all_content[$i]['jb_uid'] = XoopsUser::getUnameFromId($jb_uid, 1);
                $all_content[$i]['jb_booking_time'] = $jb_booking_time;
                $all_content[$i]['jb_booking_content'] = $jb_booking_content;
                $all_content[$i]['jb_start_date'] = $jb_start_date;
                $all_content[$i]['jb_end_date'] = $jb_end_date;
                $all_content[$i]['had_pass'] = get_had_pass($jbt_sn, $jb_date);
                $i++;
            }
            $xoopsTpl->assign('bar', $bar);
            $xoopsTpl->assign('all_content', $all_content);
        }
    }
    //die(var_dump($all_content));
    //刪除確認的JS
    $sweet_alert = new SweetAlert();
    $sweet_alert->render('delete_jill_booking_func', "{$_SERVER['PHP_SELF']}?op=delete_booking&jb_info=", "jb_info");

    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
    $xoopsTpl->assign('item_opt', $item_opt);
    $xoopsTpl->assign('now_op', 'jill_booking_approvallist');

}
//審核通過
function update_jb_status($jb_sn = "", $jb_date = "", $jbt_sn = "", $jbi_sn = "")
{
    global $xoopsDB, $xoopsUser, $xoopsLogger;
    header('HTTP/1.1 200 OK');
    $xoopsLogger->activated = false;
    $uid = $xoopsUser->uid();
    $myts = \MyTextSanitizer::getInstance();
    //正確抓取XOOPS時間
    $now = date('Y-m-d', xoops_getUserTimestamp(time()));
    $itemArr = get_jill_booking_item($jbi_sn, 1);
    $checkapproval = explode(",", $itemArr['jbi_approval']);

    if (in_array($uid, $checkapproval)) {
        $sql = 'UPDATE `' . $xoopsDB->prefix('jill_booking_date') . '` SET `jb_status` = ?, `approver` = ?, `pass_date` = ? WHERE `jb_sn` = ? AND `jb_date` = ? AND `jbt_sn` = ?';
        Utility::query($sql, 'sisisi', ['1', $uid, $now, $jb_sn, $jb_date, $jbt_sn]) or die('0');

        //寄送email
        $member_handler = &xoops_gethandler('member');

        $bookingArr = get_jill_booking($jb_sn);
        $timeArr = get_jill_booking_time($jbt_sn);

        $jb_week = date('w', strtotime($jb_date));

        $booking = XoopsUser::getUnameFromId($bookingArr['jb_uid'], 1);
        $booking_user = &$member_handler->getUser($bookingArr['jb_uid']);
        $mail_title = "Auto-Reply:{$booking}於 {$jb_date} {$itemArr['jbi_title']} 審核通過";
        $mail_content = Utility::html5("{$booking}於 {$jb_date} 預約了{$itemArr['jbi_title']}:星期 {$jb_week} {$timeArr['jbt_title']}審核通過");
        if (is_object($booking_user)) {
            $booking_email = $myts->htmlSpecialChars($booking_user->getVar('email'));
            Tools::send_now($booking_email, $mail_title, $mail_content);
        }

        foreach ($checkapproval as $key => $approval_uid) {
            $user = &$member_handler->getUser($approval_uid);
            if (is_object($user)) {
                $email = $myts->htmlSpecialChars($user->getVar('email'));
                Tools::send_now($email, $mail_title, $mail_content);
            }
        }

    }

    die('1');

}
//取得審核通過者
function get_had_pass($jbt_sn = "", $jb_date = "")
{
    global $xoopsDB;
    $sql = 'SELECT a.`pass_date`, b.`jb_uid` FROM `' . $xoopsDB->prefix('jill_booking_date') . '` AS a
    JOIN `' . $xoopsDB->prefix('jill_booking') . '` AS b ON b.`jb_sn`=a.`jb_sn`
    WHERE a.`pass_date` !=? AND a.`jb_date` =? AND a.`jbt_sn` =? AND a.`jb_waiting` = 1 ORDER BY a.`jb_waiting`';
    $result = Utility::query($sql, 'ssi', ['0000-00-00', $jb_date, $jbt_sn]);

    list($pass_date, $jb_uid) = $xoopsDB->fetchRow($result);
    //列出預約者資訊
    $users = '';
    if (!empty($jb_uid)) {
        $users = $pass_date . _MD_JILLBOOKIN_HADPASS . XoopsUser::getUnameFromId($jb_uid, 1);
    }

    return $users;
}
