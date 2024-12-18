<?php
use Xmf\Request;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\Jeditable;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = 'jill_booking_adm_time.tpl';
include_once "header.php";
include_once "../function.php";
header('HTTP/1.1 200 OK');
$xoopsLogger->activated = false;
/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$jb_sn = Request::getInt('jb_sn');
$jbt_sn = Request::getInt('jbt_sn');
$jbi_sn = Request::getInt('jbi_sn');
$to_jbi_sn = Request::getInt('to_jbi_sn');

switch ($op) {
/*---判斷動作請貼在下方---*/

//新增資料
    case "insert_jill_booking_time":
        $jbi_sn = insert_jill_booking_time();
        header("location: {$_SERVER['PHP_SELF']}?jbi_sn=$jbi_sn");
        break;

    case "delete_jill_booking_time":
        delete_jill_booking_time($jbt_sn);
        header("location: {$_SERVER['PHP_SELF']}?jbi_sn=$jbi_sn");
        break;

    case "copy_time":
        copy_time($jbi_sn, $to_jbi_sn);
        header("location: {$_SERVER['PHP_SELF']}?op=list_jill_booking_time&jbi_sn={$to_jbi_sn}");
        break;

    case "import_time":
        import_time($jbi_sn, $_GET['type']);
        header("location: {$_SERVER['PHP_SELF']}?op=list_jill_booking_time&jbi_sn={$jbi_sn}");
        break;

    case "change_enable":
        $new_pic = change_enable($jbt_sn, $_POST['week']);
        die($new_pic);
        break;

    case "save_jbt_title":
        save_jbt_title($jbt_sn);
        die($_POST['value']);
        break;

    default:
        list_jill_booking_time($jbi_sn);
        break;

        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign("jill_book_adm", $jill_book_adm);
include_once 'footer.php';

/*-----------功能函數區--------------*/

//自動取得jill_booking_time的最新排序
function jill_booking_time_max_sort($jbi_sn = "")
{
    global $xoopsDB;
    $sql = 'SELECT MAX(`jbt_sort`) FROM `' . $xoopsDB->prefix('jill_booking_time') . '` WHERE `jbi_sn`=?';
    $result = Utility::query($sql, 'i', [$jbi_sn]);
    list($sort) = $xoopsDB->fetchRow($result);
    return ++$sort;
}

//新增資料到jill_booking_time中
function insert_jill_booking_time()
{
    global $xoopsDB;

    $week = implode(",", $_POST['jbt_week']);

    $jbt_sort = jill_booking_time_max_sort($_POST['jbi_sn']);
    $sql = 'INSERT INTO `' . $xoopsDB->prefix('jill_booking_time') . '` (`jbi_sn`, `jbt_title`, `jbt_sort`, `jbt_week`) VALUES (?, ?, ?, ?)';
    Utility::query($sql, 'isis', [$_POST['jbi_sn'], $_POST['jbt_title'], $jbt_sort, $week]);

    return $_POST['jbi_sn'];
}

//刪除jill_booking_time某筆資料資料
function delete_jill_booking_time($jbt_sn = "")
{
    global $xoopsDB;
    if (empty($jbt_sn)) {
        return;
    }

    $sql = 'DELETE FROM `' . $xoopsDB->prefix('jill_booking_time') . '` WHERE `jbt_sn` = ?';
    Utility::query($sql, 'i', [$jbt_sn]);
}

//列出所有jill_booking_time資料
function list_jill_booking_time($jbi_sn = "")
{
    global $xoopsDB, $xoopsTpl;
    if (empty($jbi_sn)) {
        return;
    }

    $item = get_jill_booking_item($jbi_sn);

    $jeditable = new Jeditable();

    $myts = \MyTextSanitizer::getInstance();
    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('jill_booking_time') . '` WHERE `jbi_sn`=? ORDER BY `jbt_sort`';
    $result = Utility::query($sql, 'i', [$jbi_sn]);
    $all_content = array();
    $i = 0;
    while ($all = $xoopsDB->fetchArray($result)) {
        //以下會產生這些變數： $jbt_sn , $jbi_sn , $jbt_title , $jbt_sort
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        //過濾讀出的變數值
        $jbt_title = $myts->htmlSpecialChars($jbt_title);

        $jeditable->setTextCol("#jbt_title_{$jbt_sn}", 'time.php', '100%', '11pt', "{'jbt_sn':$jbt_sn,'op' : 'save_jbt_title'}", _TAD_EDIT . _MA_JILLBOOKIN_JBT_TITLE);

        $all_content[$i]['jbi_sn'] = $jbi_sn;
        $all_content[$i]['jbt_sn'] = $jbt_sn;
        $all_content[$i]['jbt_title_link'] = "<a href='{$_SERVER['PHP_SELF']}?jbt_sn={$jbt_sn}'>{$jbt_title}</a>";
        $all_content[$i]['jbt_title'] = $jbt_title;
        $all_content[$i]['jbt_sort'] = $jbt_sort;
        $all_content[$i]['jbt_week'] = strval($jbt_week);
        $booking_times = get_booking_times($jbt_sn);
        $all_content[$i]['booking_times'] = empty($booking_times) ? "" : sprintf(_MA_JILLBOOKIN_BOOKING_TIME, $booking_times);
        $w_arr = explode(',', $jbt_week);

        for ($j = 0; $j <= 7; $j++) {
            $name = "w{$j}";
            if ($jbt_week == null) {
                $pic = "no.gif";
            } else {
                $pic = in_array($j, $w_arr) ? "yes.gif" : "no.gif";
            }

            $all_content[$i][$name] = "<img src='../images/{$pic}' id='{$jbt_sn}_{$j}' onClick=\"change_enable($jbt_sn,$j);\" style='cursor: pointer;'>";
        }
        ++$i;
    }

    //die(var_export($all_content));
    //刪除確認的JS

    $xoopsTpl->assign('item', $item);
    $xoopsTpl->assign('action', $_SERVER['PHP_SELF']);
    $xoopsTpl->assign('all_content', $all_content);
    $xoopsTpl->assign('now_op', 'list_jill_booking_time');
    $xoopsTpl->assign('jbi_sn', $jbi_sn);

    $sweet_alert = new SweetAlert();
    $sweet_alert->render('delete_jill_booking_time_func', "{$_SERVER['PHP_SELF']}?op=delete_jill_booking_time&jbi_sn=$jbi_sn&jbt_sn=", "jbt_sn");

    //套用formValidator驗證機制
    $formValidator = new FormValidator("#myForm", true);
    $formValidator_code = $formValidator->render();
    $xoopsTpl->assign('formValidator_code', $formValidator_code);

    $jeditable_set = $jeditable->render();
    $xoopsTpl->assign('jeditable_set', $jeditable_set);

    //找出現有場地
    $i = 0;
    $place_time = array();
    $sql = 'SELECT a.*, COUNT(b.jbt_sn) AS counter FROM `' . $xoopsDB->prefix('jill_booking_item') . '` AS a JOIN `' . $xoopsDB->prefix('jill_booking_time') . '` AS b ON a.jbi_sn=b.jbi_sn WHERE a.jbi_enable=1 GROUP BY b.jbi_sn';
    $result = Utility::query($sql);
    while ($data = $xoopsDB->fetchArray($result)) {
        $data['jbi_link'] = sprintf(_MA_JILLBOOKIN_IMPORT_PLACE, $data['jbi_title'], $data['counter']);
        $place_time[$i] = $data;
        $i++;
    }
    $xoopsTpl->assign('place_time', $place_time);
    // Utility::get_jquery(true);
}

//取得該節被預約的次數
function get_booking_times($jbt_sn = "")
{
    global $xoopsDB;

    $sql = 'SELECT COUNT(*) FROM `' . $xoopsDB->prefix('jill_booking_date') . '` WHERE `jbt_sn`=?';
    $result = Utility::query($sql, 'i', [$jbt_sn]);
    list($all) = $xoopsDB->fetchRow($result);
    return $all;
}

//複製時間區段
function copy_time($jbi_sn = "", $to_jbi_sn = "")
{
    global $xoopsDB;

    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('jill_booking_time') . '` WHERE `jbi_sn` =?';
    $result = Utility::query($sql, 'i', [$jbi_sn]);

    while ($all = $xoopsDB->fetchArray($result)) {
        //以下會產生這些變數： $jbt_sn,$jbi_sn,$jbt_title,$jbt_sort
        foreach ($all as $k => $v) {
            $$k = $v;
        }
        $ins[] = "('{$to_jbi_sn}' , '{$jbt_title}' , '{$jbt_sort}','{$jbt_week}')";
    }

    $ins_sql = implode(",", $ins);

    $sql = 'INSERT INTO `' . $xoopsDB->prefix('jill_booking_time') . '` (`jbi_sn`, `jbt_title`, `jbt_sort`, `jbt_week`) VALUES' . $ins_sql;
    Utility::query($sql);
}

//從範本快速匯入時段設定
function import_time($jbi_sn = "", $type = "")
{
    global $xoopsDB;
    if ($type == '18') {
        for ($i = 1; $i <= 8; $i++) {
            $jbt_title = sprintf(_MA_JILLBOOKIN_N_TIME, $i);
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('jill_booking_time') . '` (`jbi_sn`, `jbt_title`, `jbt_sort`, `jbt_week`) VALUES (?, ?, ?, ?)';
            Utility::query($sql, 'isis', [$jbi_sn, $jbt_title, $i, '1,2,3,4,5']);
        }
    } elseif ($type == 'apm') {
        $apm_arr[1] = _MA_JILLBOOKIN_AM;
        $apm_arr[2] = _MA_JILLBOOKIN_PM;
        for ($i = 1; $i <= 2; $i++) {
            $jbt_title = $apm_arr[$i];
            $sql = 'INSERT INTO `' . $xoopsDB->prefix('jill_booking_time') . '` (`jbi_sn`, `jbt_title`, `jbt_sort`, `jbt_week`) VALUES (?, ?, ?, ?)';
            Utility::query($sql, 'isis', [$jbi_sn, $jbt_title, $i, '1,2,3,4,5']);
        }
    }
}

//改變啟用狀態
function change_enable($jbt_sn = "", $week = "")
{
    global $xoopsDB;
    $time = get_jill_booking_time($jbt_sn);
    $jbt_week = strval($time['jbt_week']);
    $week_arr = explode(',', $jbt_week);
    if (in_array($week, $week_arr)) {
        foreach ($week_arr as $w) {
            if ($w != $week) {
                $new_week[] = $w;
            }
        }
        $new_week = implode(',', $new_week);
        $new_pic = "../images/no.gif";
    } else {
        $week_arr[] = $week;
        sort($week_arr);
        $new_week = implode(',', $week_arr);
        $new_pic = "../images/yes.gif";
    }

    $sql = 'UPDATE `' . $xoopsDB->prefix('jill_booking_time') . '` SET `jbt_week`=? WHERE `jbt_sn`=?';
    Utility::query($sql, 'si', [$new_week, $jbt_sn]);

    return $new_pic;
}

function save_jbt_title($jbt_sn)
{
    global $xoopsDB;

    $jbt_title = (string) $_POST['value'];
    $sql = 'UPDATE `' . $xoopsDB->prefix('jill_booking_time') . '` SET `jbt_title` = ? WHERE `jbt_sn` = ?';
    Utility::query($sql, 'si', [$jbt_title, $jbt_sn]);
}
