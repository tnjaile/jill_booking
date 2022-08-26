<?php
use Xmf\Request;
use XoopsModules\Tadtools\Tmt;
use XoopsModules\Tadtools\Utility;

$xoopsOption['template_main'] = 'jill_booking_adm_approval.tpl';
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
    $op = "save_jbi_approval";
    $sql = "select `uid`,`name`,`uname` from `" . $xoopsDB->prefix("users") . "` order by `uname` ";
    $result = $xoopsDB->query($sql) or Utility::web_error($sql);
    // $all_content = array();
    // $all_content2 = array();
    $from_arr = $to_arr = [];
    $i = 0;
    while (list($uid, $name, $uname) = $xoopsDB->fetchRow($result)) {
        $jbi_approvalArr = explode(",", $DBV['jbi_approval']);
        if (in_array($uid, $jbi_approvalArr)) {
            // $all_content2[$i]['uid'] = $uid;
            // $all_content2[$i]['name'] = $name;
            // $all_content2[$i]['uname'] = $uname;
            $to_arr[$uid] = "{$name} ({$uname})";

        } else {
            // $all_content[$i]['uid'] = $uid;
            // $all_content[$i]['name'] = $name;
            // $all_content[$i]['uname'] = $uname;
            $from_arr[$uid] = "{$name} ({$uname})";
        }

        $i++;
    }
    //加入Token安全機制
    include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";
    $token = new \XoopsFormHiddenToken();
    $token_form = $token->render();
    $xoopsTpl->assign("token_form", $token_form);
    $xoopsTpl->assign('action', $_SERVER["PHP_SELF"]);
    // $xoopsTpl->assign('all_content', $all_content);
    // $xoopsTpl->assign('all_content2', $all_content2);
    $xoopsTpl->assign('jbi_title', sprintf(_MA_JILLBOOKIN_APPROVAL, $DBV['jbi_title']));
    $xoopsTpl->assign('jbi_sn', $jbi_sn);
    $xoopsTpl->assign('now_op', 'jbi_approval_form');
    $xoopsTpl->assign('jbi_approval', $DBV['jbi_approval']);
    $xoopsTpl->assign('next_op', $op);

    $hidden_arr = ['op' => $op, 'jbi_sn' => $jbi_sn];
    $tmt_box = Tmt::render('jbi_approval', $from_arr, $to_arr, $hidden_arr);
    $xoopsTpl->assign('tmt_box', $tmt_box);
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

    $sql = "update `" . $xoopsDB->prefix("jill_booking_item") . "` set `jbi_approval` = '{$_POST['jbi_approval']}'  where `jbi_sn` = '$jbi_sn'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql);

    return $jbi_sn;
}
/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$jbi_sn = Request::getInt('jbi_sn');

switch ($op) {
    /*---判斷動作請貼在下方---*/

    case "save_jbi_approval":
        $jbi_sn = save_jbi_approval($jbi_sn);
        header("location: main.php");
        exit;

    default:
        jbi_approval_form($jbi_sn);
        break;

        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign("isAdmin", true);
$xoTheme->addStylesheet('/modules/tadtools/css/font-awesome/css/font-awesome.css');
$xoTheme->addStylesheet(XOOPS_URL . "/modules/tadtools/css/xoops_adm{$_SEESION['bootstrap']}.css");
include_once 'footer.php';
