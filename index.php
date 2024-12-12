<?php
use Xmf\Request;
use XoopsModules\Jill_booking\Tools;
use XoopsModules\Tadtools\Jeditable;
use XoopsModules\Tadtools\Utility;

/*-----------引入檔案區--------------*/
require_once __DIR__ . '/header.php';
$xoopsOption['template_main'] = "jill_booking_index.tpl";
require_once XOOPS_ROOT_PATH . '/header.php';
if (!class_exists('XoopsModules\Jill_booking\Tools')) {
    require XOOPS_ROOT_PATH . '/modules/jill_booking/preloads/autoloader.php';
}

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$jb_sn = Request::getInt('jb_sn');
$jbt_sn = Request::getInt('jbt_sn');
$jbi_sn = Request::getInt('jbi_sn');

switch ($op) {
/*---判斷動作請貼在下方---*/

    case 'save_jb_booking_content':
        save_jb_booking_content($jb_sn);
        break;

    case "single_insert_booking":
        header('HTTP/1.1 200 OK');
        $xoopsLogger->activated = false;
        $jb_sn = insert_jill_booking(1);
        $jb_week[$jbt_sn] = date("w", strtotime($_POST['jb_date']));
        insert_jill_booking_week($jb_sn, $jbt_sn, 1, $jb_week);
        insert_jill_booking_date($jb_sn, 1, $jbi_sn);
        $jeditable = new Jeditable();
        $jeditable->setTextCol("#jb_booking_content{$jb_sn}", 'index.php', '100px', '1.5em', "{'jb_sn':'{$jb_sn}', 'op' : 'save_jb_booking_content'}", "Edit");
        $jeditablecode = $jeditable->render('force');
        $icon = $jeditablecode . delete_booking_icon($jbt_sn - 1, $jb_week[$jbt_sn], $jbt_sn, $_POST['jb_date'], $jbi_sn) . "<div id='jb_booking_content{$jb_sn}' class='edit show_reason'>" . _MD_INDIVIDUAL_BOOKING . "</div>";
        die($icon);
        break;

    case "delete_booking":
        if (is_date($_REQUEST['jb_date']) == 1) {
            //die($jbt_sn."==".$jbi_sn."==".$_REQUEST['jb_date']);
            delete_booking($jbt_sn, $_REQUEST['jb_date'], $jbi_sn);
            header("location: {$_SERVER['PHP_SELF']}?op=booking_table&jbi_sn={$jbi_sn}&getdate={$_REQUEST['jb_date']}");
        }

        break;

    case "jill_booking_form":
        if (is_date($_REQUEST['jb_date']) == 1) {
            jill_booking_form($jbt_sn, $_REQUEST['jb_date']);
        }
        break;

    case "booking_table":
        if (is_date($_REQUEST['getdate']) == 1) {
            booking_table($jbi_sn, $_REQUEST['getdate']);
        } else {
            booking_table($jbi_sn);
        }
        break;

    default:
        booking_table($jbi_sn);
        break;

        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign("toolbar", Utility::toolbar_bootstrap($interface_menu, false, $interface_icon));
$xoopsTpl->assign('jill_book_adm', $jill_book_adm);
$xoTheme->addStylesheet('modules/jill_booking/css/module.css');
include_once XOOPS_ROOT_PATH . '/footer.php';

/*-----------功能函數區--------------*/
function booking_table($jbi_sn = "", $getdate = "")
{
    global $xoopsTpl, $xoopsUser, $xoopsModuleConfig, $xoTheme;
    $uid = !empty($xoopsUser) ? $xoopsUser->uid() : "";
    //場地設定
    $item_opt = get_jill_booking_time_options($jbi_sn);

    if (!empty($jbi_sn)) {
        //可啟用場地資訊
        $itemArr = get_jill_booking_item($jbi_sn, 1);

        $jbi_sn = empty($itemArr['jbi_sn']) ? "" : $itemArr['jbi_sn'];
        if (!empty($itemArr)) {
            //場地預約起始日期
            $start = strtotime($itemArr['jbi_start']);
            $now = strtotime(date('Y-m-d'));
            $start = ($start <= $now) ? $now : $start;

            //設定可預約之週數及日期
            $max_bookingweek = $xoopsModuleConfig['max_bookingweek'];
            //$show_range      = date("Y-m-d", strtotime("+$max_bookingweek week"));
            if (empty($max_bookingweek)) {
                //場地預約結束日期
                $end = ($itemArr['jbi_end'] == '0000-00-00') ? 0 : strtotime($itemArr['jbi_end']);
            } else {
                $endtime = strtotime("+$max_bookingweek week");
                if ($itemArr['jbi_end'] == '0000-00-00') {
                    $end = $endtime;
                } else {
                    $end = (strtotime($itemArr['jbi_end']) >= $endtime) ? $endtime : strtotime($itemArr['jbi_end']);
                }
            }
            //時段資訊
            $timeArr = Tools::get_bookingtime_jbisn($jbi_sn);
            $xoopsTpl->assign('timeArr', $timeArr);

            //週曆
            $getdate = (empty($getdate)) ? date("Y-m-d") : $getdate;
            $weekArr = Tools::weekArr($getdate);

            //產生預約者資訊表格狀態值
            $bookingArr = array();

            $jeditable = new Jeditable();
            //比對產生表單的陣列
            foreach ($timeArr as $t => $time) {
                $jbt_week = strval($time['jbt_week']);

                foreach ($weekArr as $wk => $weekinfo) {
                    //預約日期
                    $item_date = strtotime($weekinfo['d']);
                    //預約者資訊
                    $jbArr = Tools::get_booking_uid($time['jbt_sn'], $weekinfo['d']);

                    //取得用了該日期時段的所有者
                    $usershtml = Tools::booking_users($time['jbt_sn'], $weekinfo['d']);
                    //將 uid 編號轉換成使用者姓名（或帳號）
                    $uid_name = XoopsUser::getUnameFromId($jbArr['jb_uid'], 1);
                    if (empty($uid_name)) {
                        $uid_name = XoopsUser::getUnameFromId($jbArr['jb_uid'], 0);
                    }
                    $uid_name = (empty($jbArr['jb_status'])) ? _MD_APPROVING . ":{$uid_name}" : $uid_name;
                    $color = "transparent";
                    $content = "";
                    $status = array();
                    //過去預約
                    if ($start > $item_date) {
                        if (!empty($jbArr['jb_uid'])) {
                            $content = $uid_name;
                            $color = "#959595";
                        }
                    } elseif (($start <= $item_date and $end >= $item_date) or $end == 0) {
                        //可預約期間
                        if (strpos($jbt_week, strval($wk)) !== false) {
                            if (empty($jbArr['jb_sn'])) {
                                if ($_SESSION['can_booking']) {
                                    $content = "";
                                    $status['func'] = "single_insert_booking";
                                    $status['time'] = $time['jbt_sn'];
                                    $status['weekinfo'] = $weekinfo['d'];
                                    $status['jbi_sn'] = $jbi_sn;
                                } else {
                                    $content = _MD_JILLBOOKIN_NO;
                                    $color = "#EA4335";
                                }
                            } else {
                                if ($uid == $jbArr['jb_uid']) {

                                    $jeditable->setTextCol("#jb_booking_content{$jbArr['jb_sn']}", 'index.php', '100px', '1.5em', "{'jb_sn':'{$jbArr['jb_sn']}', 'op' : 'save_jb_booking_content'}", "Edit");

                                    if ($_SESSION['can_booking']) {
                                        $content = delete_booking_icon($t, $wk, $time['jbt_sn'], $weekinfo['d'], $jbi_sn) . $usershtml . "<div id='jb_booking_content{$jbArr['jb_sn']}' class='edit show_reason'>{$jbArr['jb_booking_content']}</div>";
                                        $color = "#000000";
                                    }
                                } else {
                                    if ($_SESSION['Isapproval']) {
                                        $content = delete_booking_icon($t, $wk, $time['jbt_sn'], $weekinfo['d'], $jbi_sn) . $usershtml . "<div class='show_reason'>{$jbArr['jb_booking_content']}</div>";
                                        $color = "#000000";
                                    } else {
                                        $content = "{$uid_name}{$usershtml}<div class='show_reason'>{$jbArr['jb_booking_content']}</div>";
                                        $color = "#000000";
                                    }

                                }
                            }
                        }
                    }
                    $bookingArr[$t][$wk]['color'] = $color;
                    $bookingArr[$t][$wk]['status'] = $status;
                    $bookingArr[$t][$wk]['content'] = $content;
                }
            }
            $jeditable->render();
            Utility::test($bookingArr, 'bookingArr', 'dd');
            $xoopsTpl->assign('bookingArr', $bookingArr);
            $xoopsTpl->assign('itemArr', $itemArr);
            $xoopsTpl->assign('weekArr', $weekArr);
            // die(var_export($itemArr));
        }
    }
    //避免js重複引入
    if ($xoTheme) {
        $xoopsTpl->assign('jquery', Utility::get_jquery(true));
        $xoTheme->addStylesheet('modules/tadtools/jquery.qtip/jquery.qtip.css');
        $xoTheme->addScript('modules/tadtools/jquery.qtip/jquery.qtip.js');
    }
    $xoopsTpl->assign('jbi_sn', $jbi_sn);
    $xoopsTpl->assign('item_opt', $item_opt);
    $xoopsTpl->assign('now_op', "booking_table");
    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
}

//產生刪除預約鈕
function delete_booking_icon($t = "", $wk = "", $jbt_sn = "", $jb_date = "", $jbi_sn = "")
{
    global $xoopsUser;
    if (!isset($xoopsUser)) {
        return;
    }
    $jbArr = Tools::get_booking_uid($jbt_sn, $jb_date);
    //將 uid 編號轉換成使用者姓名（或帳號）
    $uid_name = XoopsUser::getUnameFromId($jbArr['jb_uid'], 1);
    $is_approval = (empty($jbArr['jb_status'])) ? _MD_APPROVING : "{$uid_name}";

    if (empty($uid_name)) {
        $uid_name = XoopsUser::getUnameFromId($jbArr['jb_uid'], 0);
    }

    $icon = "{$is_approval}<a href=\"javascript:delete_booking({$t} , {$wk} , {$jbt_sn},'{$jb_date}',{$jbi_sn});\" style='color:#D44950;' ><i class='fa fa-times' ></i></a>";
    return $icon;
}

function save_jb_booking_content($jb_sn)
{
    global $xoopsDB;

    $value = (string) $_POST['value'];

    $sql = 'UPDATE `' . $xoopsDB->prefix('jill_booking') . '` SET `jb_booking_content`=? WHERE `jb_sn`=?';
    Utility::query($sql, 'si', [$value, $jb_sn]);
    die($value);
}
