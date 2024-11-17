<?php
use XoopsModules\Tadtools\Utility;
xoops_loadLanguage('main', 'tadtools');

/********************* 資料庫函數 *********************/
//以流水號取得某筆jill_booking_item資料
function get_jill_booking_item($jbi_sn = "", $jbi_enable = "")
{
    global $xoopsDB;
    if (empty($jbi_sn)) {
        return;
    }

    $where = ($jbi_enable == "1") ? " WHERE `jbi_sn` = ? AND `jbi_enable` = ? AND ((NOW() BETWEEN `jbi_start` AND `jbi_end`) OR  (TO_DAYS(NOW()) - TO_DAYS(`jbi_start`) >=0 AND `jbi_end`='0000-00-00')) " : " WHERE `jbi_sn` = ?";
    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('jill_booking_item') . '` ' . $where;
    $params = ($jbi_enable == "1") ? ['is', [$jbi_sn, $jbi_enable]] : ['i', [$jbi_sn]];
    $result = Utility::query($sql, ...$params);

    $data = $xoopsDB->fetchArray($result);
    return $data;
}

//取得場地選項(列出有時段設定者)
function get_jill_booking_time_options($def_jbi_sn = "", $approval = "")
{
    global $xoopsDB;
    // $where_approval = (empty($approval)) ? "" : " && (`jbi_approval` LIKE '%,{$approval}' || `jbi_approval` LIKE '{$approval},%' || `jbi_approval`='{$approval}')";
    $sql = 'SELECT `jbi_sn`,`jbi_title`,`jbi_approval` FROM `' . $xoopsDB->prefix('jill_booking_item') . '` WHERE `jbi_enable`=1 AND ((NOW() BETWEEN `jbi_start` AND `jbi_end`) OR (TO_DAYS(NOW()) - TO_DAYS(`jbi_start`) >=0 AND `jbi_end`="0000-00-00")) ORDER BY `jbi_sort`';
    $result = Utility::query($sql);

    $opt = "";
    while ($all = $xoopsDB->fetchArray($result)) {
        //以下會產生這些變數： $jbt_sn , $jbi_sn , $jbi_title
        foreach ($all as $k => $v) {
            $$k = $v;
        }

        $jbi_approval_arr = explode(',', $jbi_approval);
        if (!empty($approval) and !in_array($approval, $jbi_approval_arr)) {
            continue;
        }

        $is_approval = (empty($jbi_approval)) ? "" : _MD_IS_APPROVAL;
        $selected = ($jbi_sn == $def_jbi_sn) ? "selected" : "";
        $opt .= "<option value='$jbi_sn' $selected>$jbi_title{$is_approval}</option>";
    }

    return $opt;
}

//以流水號取得某筆jill_booking資料
function get_jill_booking($jb_sn = "")
{
    global $xoopsDB;
    if (empty($jb_sn)) {
        return;
    }

    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('jill_booking') . '` WHERE `jb_sn` = ?';
    $result = Utility::query($sql, 'i', [$jb_sn]);
    $data = $xoopsDB->fetchArray($result);
    return $data;
}

//以流水號取得某筆jill_booking_time資料
function get_jill_booking_time($jbt_sn = "")
{
    global $xoopsDB;
    if (empty($jbt_sn)) {
        return;
    }

    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('jill_booking_time') . '` WHERE `jbt_sn` = ?';
    $result = Utility::query($sql, 'i', [$jbt_sn]);
    $data = $xoopsDB->fetchArray($result);
    return $data;
}
//取得用了該日期時段的uid
function get_bookingArr($jbt_sn = "", $jb_date = "")
{
    global $xoopsDB, $xoopsUser, $jill_book_adm;
    if (!isset($xoopsUser)) {
        return;
    }

    $uid = $xoopsUser->uid();
    $where = ($_SESSION['Isapproval'] or $jill_book_adm) ? "WHERE a.`jbt_sn`=? AND a.`jb_date`=? " : "WHERE a.`jbt_sn`=? AND a.`jb_date`=? AND b.`jb_uid`=?";

    $sql = 'SELECT a.`jb_waiting`, b.`jb_sn`, b.`jb_uid`, b.`jb_booking_time`, b.`jb_booking_content`, b.`jb_start_date`, b.`jb_end_date`
            FROM `' . $xoopsDB->prefix('jill_booking_date') . '` AS a
            JOIN `' . $xoopsDB->prefix('jill_booking') . '` AS b
            ON a.`jb_sn`=b.`jb_sn` ' . $where . '
            ORDER BY a.`jb_waiting`';
    $params = ($_SESSION['Isapproval'] or $jill_book_adm) ? ['is', [$jbt_sn, $jb_date]] : ['isi', [$jbt_sn, $jb_date, $uid]];
    $result = Utility::query($sql, ...$params);

    $data = $xoopsDB->fetchArray($result);
    //die(var_export($data));
    return $data;
}
//新增資料到jill_booking中
function insert_jill_booking($single = "")
{
    global $xoopsDB, $xoopsUser;
    //取得使用者編號
    $uid = $xoopsUser->uid();

    if ($single == 1) {
        $jb_booking_content = _MD_INDIVIDUAL_BOOKING;
    } else {
        $jb_booking_content = empty($_POST['jb_booking_content']) ? _MD_JILLBOOKIN_SMNAME2 : $_POST['jb_booking_content'];
    }

    $jb_start_date = ($single == 1) ? $_POST['jb_date'] : $_POST['jb_start_date'];
    if ($single == 1) {
        $jb_end_date = $_POST['jb_date'];
    } else {
        $endtime = $_POST['jb_end_date'];
        $max_date = $_POST['max_date'];
        $jb_end_date = (strtotime($max_date) < strtotime($endtime)) ? $max_date : $endtime;
    }
    $jb_week = ($single == 1) ? date("w", strtotime($_POST['jb_date'])) : $jb_weekArr;
    //新增到jill_booking
    $sql = 'INSERT INTO `' . $xoopsDB->prefix('jill_booking') . '` (`jb_uid`, `jb_booking_time`, `jb_booking_content`, `jb_start_date`, `jb_end_date`) VALUES (?, ?, ?, ?, ?)';
    Utility::query($sql, 'issss', [$uid, date('Y-m-d H:i:s', xoops_getUserTimestamp(time())), $jb_booking_content, $jb_start_date, $jb_end_date]);

    //取得最後新增資料的流水編號
    $jb_sn = $xoopsDB->getInsertId();
    return $jb_sn;
}
//新增資料到jill_booking_week中
function insert_jill_booking_week($jb_sn = "", $jbt_sn = "", $single = "", $jb_week = "")
{
    global $xoopsDB;
    if (empty($jbt_sn) or empty($jb_sn)) {
        return;
    }

    //die(var_export($jb_week));
    //新增到jill_booking_week
    foreach ($jb_week as $key => $week) {
        $sql = 'INSERT INTO `' . $xoopsDB->prefix('jill_booking_week') . '` (`jb_sn`, `jb_week`, `jbt_sn`) VALUES (?, ?, ?)';
        Utility::query($sql, 'iii', [$jb_sn, $week, $jbt_sn]);
    }
}
//新增資料到jill_booking中
function insert_jill_booking_date($jb_sn = "", $single = "", $jbi_sn = "")
{
    global $xoopsDB, $xoopsUser;
    if (empty($jb_sn)) {
        return;
    }
    $itemArr = get_jill_booking_item($jbi_sn, 1);

    $jb_status = (empty($itemArr['jbi_approval'])) ? 1 : 0;
    //新增到jill_booking_date
    if ($single == 1) {
        $jb_waiting = jb_waiting_max_sort($_POST['jb_date'], $_POST['jbt_sn']);

        $sql = 'INSERT INTO `' . $xoopsDB->prefix('jill_booking_date') . '` (`jb_sn`, `jb_date`, `jbt_sn`, `jb_waiting`, `jb_status`, `approver`, `pass_date`) VALUES (?, ?, ?, ?, ?, ?, ?)';
        Utility::query($sql, 'isiisis', [$jb_sn, $_POST['jb_date'], $_POST['jbt_sn'], $jb_waiting, $jb_status, 0, '000-00-00']);
        //return $xoopsUser->name();
    } else {
        foreach ($_POST['jb_date'] as $jbt_sn => $dateArr) {
            foreach ($dateArr as $jb_date) {
                $jb_waiting = jb_waiting_max_sort($jb_date, $jbt_sn);

                $sql = 'INSERT INTO `' . $xoopsDB->prefix('jill_booking_date') . '` (`jb_sn`, `jb_date`, `jbt_sn`, `jb_waiting`, `jb_status`, `approver`, `pass_date`) VALUES(?, ?, ?, ?, ?,?,?)';
                Utility::query($sql, 'ssiisis', [$jb_sn, $jb_date, $jbt_sn, $jb_waiting, $jb_status, 0, '000-00-00']);
            }
        }
        return $_POST['jbi_sn'];
    }
}
//自動取得jill_booking候補值
function jb_waiting_max_sort($jb_date = "", $jbt_sn = "")
{
    global $xoopsDB;
    $sql = 'SELECT MAX(`jb_waiting`) FROM `' . $xoopsDB->prefix('jill_booking_date') . '` WHERE `jb_date`=? AND `jbt_sn`=?';
    $result = Utility::query($sql, 'si', [$jb_date, $jbt_sn]);

    list($sort) = $xoopsDB->fetchRow($result);
    return ++$sort;
}

//刪除jill_booking某筆資料資料
function delete_jill_booking($jb_sn = "")
{
    global $xoopsDB;

    if (empty($jb_sn)) {
        return;
    }
    $sql = 'DELETE FROM `' . $xoopsDB->prefix('jill_booking') . '` WHERE `jb_sn` = ?';
    Utility::query($sql, 'i', [$jb_sn]);

}

//刪除jill_booking_date某筆資料資料
function delete_jill_booking_date($jb_sn = "", $jb_date = "", $jbt_sn = "")
{
    global $xoopsDB;

    if (empty($jb_sn) or empty($jb_date) or empty($jbt_sn)) {
        return;
    }

    $sql = 'SELECT `jb_waiting` FROM `' . $xoopsDB->prefix('jill_booking_date') . '` WHERE `jb_sn`=? AND `jb_date`=? AND `jbt_sn`=?';
    $result = Utility::query($sql, 'isi', [$jb_sn, $jb_date, $jbt_sn]);
    list($seed_waiting) = $xoopsDB->fetchRow($result);

    $sql = 'DELETE FROM `' . $xoopsDB->prefix('jill_booking_date') . '` WHERE `jb_sn`=? AND `jb_date`=? AND `jbt_sn`=?';
    Utility::query($sql, 'isi', [$jb_sn, $jb_date, $jbt_sn]);
    //更新jb_waiting
    $sql = 'SELECT * FROM `' . $xoopsDB->prefix('jill_booking_date') . '` WHERE `jb_date`=? AND `jbt_sn` =?';
    $result = Utility::query($sql, 'si', [$jb_date, $jbt_sn]);
    while ($all = $xoopsDB->fetchArray($result)) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }
        if ($jb_waiting > $seed_waiting) {
            $jb_waiting = $jb_waiting - 1;
            $sql = 'UPDATE `' . $xoopsDB->prefix('jill_booking_date') . '` SET `jb_waiting`=? WHERE `jb_sn`=? AND `jb_date`=? AND `jbt_sn`=?';
            Utility::query($sql, 'iisi', [$jb_waiting, $jb_sn, $jb_date, $jbt_sn]) or die(_TAD_SORT_FAIL . ' (' . date('Y-m-d H:i:s') . ')' . $sql);
        }

    }

}
//刪除jill_booking_week某筆資料資料
function delete_jill_booking_week($jb_sn = "", $jb_date = "", $jbt_sn = "")
{
    global $xoopsDB;

    if (empty($jb_sn) or empty($jb_date) or empty($jbt_sn)) {
        return;
    }

    $jb_week = date("w", strtotime($jb_date));
    $sql = 'DELETE FROM `' . $xoopsDB->prefix('jill_booking_week') . '` WHERE `jb_sn`=? AND `jb_week`=? AND `jbt_sn`=?';
    Utility::query($sql, 'iii', [$jb_sn, $jb_week, $jbt_sn]);

}
//取消預約
function delete_booking($jbt_sn = "", $jb_date = "", $jbi_sn = "")
{
    if (empty($jbt_sn) or empty($jb_date)) {
        return;
    }

    if (empty($_SESSION['can_booking']) && empty($_SESSION['Isapproval'])) {
        return;
    }
    $bookingArr = get_bookingArr($jbt_sn, $jb_date);

    if (empty($bookingArr)) {
        return;
    }

    delete_jill_booking_date($bookingArr['jb_sn'], $jb_date, $jbt_sn);

    if (strtotime($bookingArr['jb_start_date']) == strtotime($bookingArr['jb_end_date'])) {
        delete_jill_booking_week($bookingArr['jb_sn'], $jb_date, $jbt_sn);
        delete_jill_booking($bookingArr['jb_sn']);
    }

    return $jbi_sn;

}
/********************* XOOPS檢查身分函數 *********************/
//檢查是否具有預約權限
function booking_perm($mode = "booking_group")
{
    global $xoopsUser, $xoopsModuleConfig, $jill_book_adm;
    $_SESSION['can_booking'] = false;
    if ($xoopsUser) {
        if ($jill_book_adm) {
            return true;
            exit;
        }
        $needle_groups = $xoopsUser->groups();
        $haystack_groups = $xoopsModuleConfig[$mode];
        foreach ($needle_groups as $key => $group) {
            if (in_array($group, $haystack_groups)) {
                return true;
            }
        }
    }
    return false;
}
//檢查是否具有審核權限
function booking_approval()
{
    global $xoopsUser, $xoopsDB, $jill_book_adm;

    $can_approval = false;
    if ($xoopsUser) {
        if ($jill_book_adm) {
            return true;
            exit;
        }

        $uid = $xoopsUser->uid();

        $sql = 'SELECT `jbi_approval` FROM `' . $xoopsDB->prefix('jill_booking_item') . '` WHERE `jbi_enable` =?';
        $result = Utility::query($sql, 's', ['1']);
        while (list($jbi_approval) = $xoopsDB->fetchRow($result)) {
            $jbi_approval_arr = explode(',', $jbi_approval);
            if (in_array($uid, $jbi_approval_arr)) {
                return true;
            }
        }
    }
    return false;
}

/********************* 自訂函數 *********************/
//檢查日期格式
function is_date($date)
{
    if (preg_match("/^[12][0-9]{3}-([0][1-9])|([1][12])-([0][1-9])|([12][0-9])|([3][01])$/", $date)) {
        return 1;
    } else {
        return 0;
    }
}
//取得批次預約日期
function getdateArr($seed_weekday = "", $start_date = "", $end_date = "")
{
    list($y, $m, $d) = explode("-", $start_date);
    $now = 0;
    $end = strtotime($end_date);
    $i = 0;
    $date_arr = array();
    while ($now < $end) {
        $now = mktime(0, 0, 0, $m, $d + $i, $y);
        if (strpos($seed_weekday, strval(date('w', $now))) !== false) {
            $date_arr[] = date("Y-m-d", $now);
        }
        $i++;
    }
    return $date_arr;
}
