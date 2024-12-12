<?php
use Xmf\Request;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$xoopsOption['template_main'] = "jill_booking_list.tpl";
require_once XOOPS_ROOT_PATH . '/header.php';

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$primary = Request::getString('primary');
$jb_sn = Request::getInt('jb_sn');
$jbi_sn = Request::getInt('jbi_sn');

switch ($op) {
/*---判斷動作請貼在下方---*/
    case "delete_jill_booking":
        $primaryArr = explode("_", $primary);
        //jbi_sn=$primaryArr[0], jb_date=$primaryArr[1], jbt_sn=$primaryArr[2]
        delete_booking($primaryArr[2], $primaryArr[1], $primaryArr[0]);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;
        break;

    default:
        jill_booking_list($jbi_sn);
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
function jill_booking_list($def_jbi_sn = "")
{
    global $xoopsDB, $xoopsTpl, $xoopsUser;
    if (!$xoopsUser) {
        return;
    }
    //場地設定
    $item_opt = get_jill_booking_time_options($def_jbi_sn);
    $xoopsTpl->assign('item_opt', $item_opt);
    $where_jbisn = empty($def_jbi_sn) ? "" : " and c.jbi_sn='{$def_jbi_sn}' ";
    $uid = $xoopsUser->uid();
    $myts = \MyTextSanitizer::getInstance();
    $sql = "select a.jb_sn,a.jb_date,a.jbt_sn,a.jb_waiting,a.jb_status,b.jb_uid,b.jb_booking_time,b.jb_booking_content,b.jb_start_date,b.jb_end_date,c.jbi_sn,c.jbt_title,c.jbt_sort,d.jbi_title,d.jbi_approval
    from `" . $xoopsDB->prefix("jill_booking_date") . "` as a
    join `" . $xoopsDB->prefix("jill_booking") . "` as b  on a.jb_sn=b.jb_sn
    join `" . $xoopsDB->prefix("jill_booking_time") . "` as c  on a.jbt_sn=c.jbt_sn
    join `" . $xoopsDB->prefix("jill_booking_item") . "` as d  on c.jbi_sn=d.jbi_sn
    where b.`jb_uid`='{$uid}' $where_jbisn order by a.jb_status desc,a.`jb_date` desc,a.`jb_waiting`,a.jbt_sn  ";

    //Utility::getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
    $PageBar = Utility::getPageBar($sql, 20, 10, null, null);
    $bar = $PageBar['bar'];
    $sql = $PageBar['sql'];
    $total = $PageBar['total'];

    $result = Utility::query($sql);

    $all_content = array();
    $i = 0;
    while ($all = $xoopsDB->fetchArray($result)) {
        //以下會產生這些變數：jb_sn,jb_date,jbt_sn,,jb_waiting,jb_status,jb_uid,jb_booking_time,jb_booking_content,jb_start_date,jb_end_date,jbi_sn,jbt_title,jbt_sort,jbi_title,jbi_approval
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        //過濾讀出的變數值
        $jb_booking_content = $myts->displayTarea($jb_booking_content, 1, 1, 0, 1, 0);
        $jb_start_date = $myts->htmlSpecialChars($jb_start_date);
        $jb_end_date = $myts->htmlSpecialChars($jb_end_date);

        $all_content[$i]['jb_sn'] = $jb_sn;
        $weekday = get_chinese_weekday($jb_date);
        $all_content[$i]['jb_date'] = $jb_date . $weekday;
        $all_content[$i]['jbt_sn'] = $jbt_sn;
        $all_content[$i]['primary'] = $jbi_sn . "_" . $jb_date . "_" . $jbt_sn;
        $all_content[$i]['jb_waiting'] = $jb_waiting;
        $all_content[$i]['jb_status'] = $jb_status;
        $all_content[$i]['jb_uid'] = XoopsUser::getUnameFromId($jb_uid, 1);
        $all_content[$i]['jb_booking_time'] = $jb_booking_time;
        $all_content[$i]['jb_booking_content'] = $jb_booking_content;
        $all_content[$i]['jb_start_date'] = $jb_start_date;
        $all_content[$i]['jb_end_date'] = $jb_end_date;
        $all_content[$i]['jbi_sn'] = $jbi_sn;
        $all_content[$i]['jbt_title'] = $jbt_title;
        $all_content[$i]['jbt_sort'] = $jbt_sort;
        $all_content[$i]['jbi_title'] = $jbi_title;
        $all_content[$i]['jbi_approval'] = $jbi_approval;
        $today = date("Y-m-d");
        $all_content[$i]['fun'] = (strtotime($jb_date) >= strtotime($today)) ? 1 : '';
        $i++;
    }

    //刪除確認的JS

    $xoopsTpl->assign('bar', $bar);
    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
    $xoopsTpl->assign('all_content', $all_content);
    $xoopsTpl->assign('now_op', 'jill_booking_list');

    $sweet_alert = new SweetAlert();
    $sweet_alert->render('delete_jill_booking_func', "{$_SERVER['PHP_SELF']}?op=delete_jill_booking&primary=", "primary");

}
function get_chinese_weekday($datetime)
{
    $cw = array('日', '一', '二', '三', '四', '五', '六');
    $weekday = date('w', strtotime($datetime));
    return '星期' . $cw[$weekday];
}
