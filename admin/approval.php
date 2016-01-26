<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tnjaile 製作
// 製作日期：2015-01-14
// $Id:$
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = 'jill_booking_adm_approval.html';
include_once "header.php";
include_once "../function.php";

/*-----------功能函數區--------------*/
function jbi_approval_form($jbi_sn = "")
{
    global $xoopsDB, $xoopsTpl;

    if (empty($jbi_sn)) {
        redirect_header("main.php", 3, "無此場地。");
    }
    $DBV = get_jill_booking_item($jbi_sn);
    if (empty($DBV)) {
        redirect_header("main.php", 3, "無此場地。");
    }
    //設定 jbi_approval 欄位的預設值
    $xoopsTpl->assign('approval', (int) $DBV['jbi_approval']);
    $op           = "save_jbi_approval";
    $sql          = "select `uid`,`name`,`uname` from `" . $xoopsDB->prefix("users") . "` order by `uname` ";
    $result       = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    $all_content  = "";
    $all_content2 = "";
    $i            = 0;
    while (list($uid, $name, $uname) = $xoopsDB->fetchRow($result)) {
        $jbi_approvalArr = explode(";", $DBV['jbi_approval']);
        if (in_array($uid, $jbi_approvalArr)) {
            $all_content2[$i]['uid']   = $uid;
            $all_content2[$i]['name']  = $name;
            $all_content2[$i]['uname'] = $uname;

        } else {
            $all_content[$i]['uid']   = $uid;
            $all_content[$i]['name']  = $name;
            $all_content[$i]['uname'] = $uname;
        }

        $i++;
    }
    //加入Token安全機制
    include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
    $token      = new XoopsFormHiddenToken();
    $token_form = $token->render();
    $xoopsTpl->assign("token_form", $token_form);
    $xoopsTpl->assign('action', $_SERVER["PHP_SELF"]);
    $xoopsTpl->assign('all_content', $all_content);
    $xoopsTpl->assign('all_content2', $all_content2);
    $xoopsTpl->assign('jbi_title', sprintf(_MA_JILLBOOKIN_APPROVAL, $DBV['jbi_title']));
    $xoopsTpl->assign('jbi_sn', $jbi_sn);
    $xoopsTpl->assign('now_op', 'jbi_approval_form');
    $xoopsTpl->assign('jbi_approval', $DBV['jbi_approval']);
    $xoopsTpl->assign('next_op', $op);
}
function save_jbi_approval($jbi_sn = "")
{
    global $xoopsDB;

    if (empty($jbi_sn)) {
        return;
    }

    //XOOPS表單安全檢查
    if (!$GLOBALS['xoopsSecurity']->check()) {
        $error = implode("<br />", $GLOBALS['xoopsSecurity']->getErrors());
        redirect_header($_SERVER['PHP_SELF'], 3, $error);
    }

    $sql = "update `" . $xoopsDB->prefix("jill_booking_item") . "` set
   `jbi_approval` = '{$_POST['jbi_approval']}'  where `jbi_sn` = '$jbi_sn'";
    //die($sql);
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

    return $jbi_sn;
}
/*-----------執行動作判斷區----------*/
$op     = empty($_REQUEST['op']) ? "" : $_REQUEST['op'];
$jbi_sn = empty($_REQUEST['jbi_sn']) ? "" : intval($_REQUEST['jbi_sn']);

switch ($op) {
    /*---判斷動作請貼在下方---*/

    case "save_jbi_approval":
        $jbi_sn = save_jbi_approval($jbi_sn);
        header("location: main.php");
        break;

    default:
        jbi_approval_form($jbi_sn);
        break;

        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign("isAdmin", true);
include_once 'footer.php';
