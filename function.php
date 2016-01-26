<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tnjaile 製作
// 製作日期：2015-01-23
// $Id:$
// ------------------------------------------------------------------------- //

//引入TadTools的函式庫
if (!file_exists(XOOPS_ROOT_PATH . "/modules/tadtools/tad_function.php")) {
    redirect_header("http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50", 3, _TAD_NEED_TADTOOLS);
}
include_once XOOPS_ROOT_PATH . "/modules/tadtools/tad_function.php";

/********************* 資料庫函數 *********************/
//以流水號取得某筆jill_booking_item資料
function get_jill_booking_item($jbi_sn = "", $jbi_enable = "")
{
    global $xoopsDB;
    if (empty($jbi_sn)) {
        return;
    }

    $where = ($jbi_enable == "1") ? " where `jbi_sn` = '{$jbi_sn}' and `jbi_enable`='{$jbi_enable}' and ((NOW() between `jbi_start` and `jbi_end`) or  (TO_DAYS(NOW()) - TO_DAYS(`jbi_start`) >=0 and `jbi_end` IS NULL)) " : " where `jbi_sn` = '{$jbi_sn}'";
    $sql   = "select * from `" . $xoopsDB->prefix("jill_booking_item") . "` $where ";
    //die($sql);
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    $data   = $xoopsDB->fetchArray($result);
    return $data;
}
//取得場地選項(列出有時段設定者)
function get_jill_booking_time_options($def_jbi_sn = "", $approval = "")
{
    global $xoopsDB, $xoopsModule;
    $where_approval = (empty($approval)) ? "" : " && (`jbi_approval` LIKE '%;{$approval}' || `jbi_approval` LIKE '{$approval};%')";
    $sql            = "select jbi_sn,jbi_title,jbi_approval from `" . $xoopsDB->prefix("jill_booking_item") . "` where jbi_enable='1' and ((NOW() between `jbi_start` and `jbi_end`) or  (TO_DAYS(NOW()) - TO_DAYS(`jbi_start`) >=0 and `jbi_end` IS NULL)) $where_approval order by jbi_sort";
    //die($sql);
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    $opt    = "";
    while ($all = $xoopsDB->fetchArray($result)) {
        //以下會產生這些變數： $jbt_sn , $jbi_sn , $jbi_title
        foreach ($all as $k => $v) {
            $$k = $v;
        }
        $is_approval = (empty($jbi_approval)) ? "" : _MD_IS_APPROVAL;
        $selected    = ($jbi_sn == $def_jbi_sn) ? "selected" : "";
        $opt .= "<option value='$jbi_sn' $selected>$jbi_title{$is_approval}</option>";
    }

    return $opt;
}
//取得用了該日期時段的uid
function get_booking_uid($jbt_sn = "", $jb_date = "")
{
    global $xoopsDB, $xoopsModule;

    $sql = "select b.jb_sn,b.jb_uid,a.jb_status from " . $xoopsDB->prefix("jill_booking_date") . " as a
  left join " . $xoopsDB->prefix("jill_booking") . " as b on a.`jb_sn`=b.`jb_sn`
   where a.`jbt_sn`='{$jbt_sn}' and a.`jb_date`='{$jb_date}' and a.`jb_waiting`='1' ";

    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    //die($sql);
    list($jb_sn, $jb_uid, $jb_status) = $xoopsDB->fetchRow($result);
    $data                             = "";
    $data['jb_sn']                    = $jb_sn;
    $data['jb_uid']                   = $jb_uid;
    $data['jb_status']                = $jb_status;
    return $data;
}

//以流水號取得某筆jill_booking資料
function get_jill_booking($jb_sn = "")
{
    global $xoopsDB;
    if (empty($jb_sn)) {
        return;
    }

    $sql    = "select * from `" . $xoopsDB->prefix("jill_booking") . "` where `jb_sn` = '{$jb_sn}'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    $data   = $xoopsDB->fetchArray($result);
    return $data;
}

//以jbi_sn取得jill_booking_time陣列
function get_bookingtime_jbisn($jbi_sn = "")
{
    global $xoopsDB;
    $sql    = "select * from `" . $xoopsDB->prefix("jill_booking_time") . "` where jbi_sn=$jbi_sn order by `jbt_sort`";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    $data   = "";
    $i      = 0;
    while ($all = $xoopsDB->fetchArray($result)) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }
        //jbt_sn,jbi_sn,jbt_title,jbt_sort,jbt_week
        $data[$i]['jbt_sn']    = $jbt_sn;
        $data[$i]['jbi_sn']    = $jbi_sn;
        $data[$i]['jbt_title'] = $jbt_title;
        $data[$i]['jbt_sort']  = $jbt_sort;
        $data[$i]['jbt_week']  = $jbt_week;
        ++$i;
    }
    //die(var_export($data));
    return $data;
}

//以流水號取得某筆jill_booking_time資料
function get_jill_booking_time($jbt_sn = "")
{
    global $xoopsDB;
    if (empty($jbt_sn)) {
        return;
    }

    $sql    = "select * from `" . $xoopsDB->prefix("jill_booking_time") . "` where `jbt_sn` = '{$jbt_sn}'";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    $data   = $xoopsDB->fetchArray($result);
    return $data;
}
//取得用了該日期時段的uid
function get_bookingArr($jbt_sn = "", $jb_date = "")
{
    global $xoopsDB, $xoopsUser, $Isapproval, $isAdmin;
    if (!isset($xoopsUser)) {
        return;
    }

    $uid   = $xoopsUser->uid();
    $where = ($Isapproval or $isAdmin) ? "where a.`jbt_sn`='{$jbt_sn}' and a.`jb_date`='{$jb_date}' " : "where a.`jbt_sn`='{$jbt_sn}' and a.`jb_date`='{$jb_date}'  and b.`jb_uid`='{$uid}'";

    $sql = "select a.`jb_waiting`,b.jb_sn,b.jb_uid,b.jb_booking_time,b.jb_booking_content,b.jb_start_date,b.jb_end_date from " . $xoopsDB->prefix("jill_booking_date") . " as a
  join " . $xoopsDB->prefix("jill_booking") . " as b on a.`jb_sn`=b.`jb_sn` $where order by a.`jb_waiting`  ";
    //die($sql);
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    $data   = $xoopsDB->fetchArray($result);
    //die(var_export($data));
    return $data;
}
//新增資料到jill_booking中
function insert_jill_booking($single = "")
{
    global $xoopsDB, $xoopsUser;
    //取得使用者編號
    $uid  = $xoopsUser->uid();
    $myts = &MyTextSanitizer::getInstance();
    if ($single == 1) {
        $jb_booking_content = _MD_INDIVIDUAL_BOOKING;
    } else {
        $jb_booking_content = (empty($_POST['jb_booking_content'])) ? _MD_JILLBOOKIN_SMNAME2 : $myts->addSlashes($_POST['jb_booking_content']);
    }

    $jb_start_date = ($single == 1) ? $myts->addSlashes($_POST['jb_date']) : $myts->addSlashes($_POST['jb_start_date']);
    if ($single == 1) {
        $jb_end_date = $myts->addSlashes($_POST['jb_date']);
    } else {
        $endtime     = $myts->addSlashes($_POST['jb_end_date']);
        $max_date    = $myts->addSlashes($_POST['max_date']);
        $jb_end_date = (strtotime($max_date) < strtotime($endtime)) ? $max_date : $endtime;
    }
    $jb_week = ($single == 1) ? date("w", strtotime($_POST['jb_date'])) : $jb_weekArr;
    //新增到jill_booking
    $sql = "insert into `" . $xoopsDB->prefix("jill_booking") . "`
  (`jb_uid` , `jb_booking_time` ,`jb_booking_content` , `jb_start_date` , `jb_end_date`)
  values('{$uid}' , '" . date("Y-m-d H:i:s", xoops_getUserTimestamp(time())) . "' ,'{$jb_booking_content}' , '{$jb_start_date}' , '{$jb_end_date}' )";
    $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

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
        $sql = "insert into `" . $xoopsDB->prefix("jill_booking_week") . "`
     (`jb_sn` , `jb_week` ,`jbt_sn`)
     values('{$jb_sn}' , '{$week}' , '{$jbt_sn}' )";
        $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
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
    $myts    = &MyTextSanitizer::getInstance();
    //$_POST['jb_date']=$myts->addSlashes($_POST['jb_date']);
    //die(var_export($_POST['jb_date']));
    $jb_status = (empty($itemArr['jbi_approval'])) ? 1 : 0;
    //新增到jill_booking_date
    if ($single == 1) {
        $jb_waiting = jb_waiting_max_sort($jb_date, $_POST['jbt_sn']);
        $sql        = "insert into `" . $xoopsDB->prefix("jill_booking_date") . "`
      (`jb_sn` , `jb_date` , `jbt_sn` , `jb_waiting`,`jb_status`)
      values('{$jb_sn}' , '{$_POST['jb_date']}' , '{$_POST['jbt_sn']}' , '{$jb_waiting}','{$jb_status}')";
        $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, $sql);
        //return $xoopsUser->name();
    } else {
        foreach ($_POST['jb_date'] as $jbt_sn => $dateArr) {
            foreach ($dateArr as $jb_date) {
                $jb_waiting = jb_waiting_max_sort($jb_date, $jbt_sn);

                $sql = "insert into `" . $xoopsDB->prefix("jill_booking_date") . "`
          (`jb_sn` , `jb_date` , `jbt_sn` , `jb_waiting`,`jb_status`)
          values('{$jb_sn}' , '{$jb_date}' , '{$jbt_sn}' , '{$jb_waiting}','{$jb_status}')";
                $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, $sql);
            }
        }
        return $_POST['jbi_sn'];
    }
}
//自動取得jill_booking候補值
function jb_waiting_max_sort($jb_date = "", $jbt_sn = "")
{
    global $xoopsDB;
    $sql        = "select max(`jb_waiting`) from `" . $xoopsDB->prefix("jill_booking_date") . "` where `jb_date`='{$jb_date}' and `jbt_sn`=$jbt_sn ";
    $result     = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    list($sort) = $xoopsDB->fetchRow($result);
    return ++$sort;
}

//刪除jill_booking某筆資料資料
function delete_jill_booking($jb_sn = "")
{
    global $xoopsDB, $isAdmin;

    if (empty($jb_sn)) {
        return;
    }

    $sql = "delete from `" . $xoopsDB->prefix("jill_booking") . "` where `jb_sn` = '{$jb_sn}'";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

}

//刪除jill_booking_date某筆資料資料
function delete_jill_booking_date($jb_sn = "", $jb_date = "", $jbt_sn = "")
{
    global $xoopsDB, $isAdmin;

    if (empty($jb_sn) or empty($jb_date) or empty($jbt_sn)) {
        return;
    }

    $sql                = "select `jb_waiting` from `" . $xoopsDB->prefix("jill_booking_date") . "` where `jb_sn`='{$jb_sn}' and `jb_date`='{$jb_date}' and `jbt_sn` = '{$jbt_sn}' ";
    $result             = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    list($seed_waiting) = $xoopsDB->fetchRow($result);

    $sql = "delete from `" . $xoopsDB->prefix("jill_booking_date") . "` where `jb_sn`='{$jb_sn}' and `jb_date`='{$jb_date}' and `jbt_sn` = '{$jbt_sn}'";
    //die($sql);
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    //更新jb_waiting
    $sql    = "select * from `" . $xoopsDB->prefix("jill_booking_date") . "` where `jb_date`='{$jb_date}' and `jbt_sn` = '{$jbt_sn}' ";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
    while ($all = $xoopsDB->fetchArray($result)) {
        foreach ($all as $k => $v) {
            $$k = $v;
        }
        if ($jb_waiting > $seed_waiting) {
            $jb_waiting = $jb_waiting - 1;
            $sql        = "update " . $xoopsDB->prefix("jill_booking_date") . " set `jb_waiting`='{$jb_waiting}' where `jb_sn`='{$jb_sn}' and `jb_date`='{$jb_date}' and `jbt_sn` = '{$jbt_sn}' ";
            $xoopsDB->queryF($sql) or die(_TAD_SORT_FAIL . " (" . date("Y-m-d H:i:s") . ")" . $sql);
        }

    }

}
//刪除jill_booking_week某筆資料資料
function delete_jill_booking_week($jb_sn = "", $jb_date = "", $jbt_sn = "")
{
    global $xoopsDB, $isAdmin;

    if (empty($jb_sn) or empty($jb_date) or empty($jbt_sn)) {
        return;
    }

    $jb_week = date("w", strtotime($jb_date));

    $sql = "delete from `" . $xoopsDB->prefix("jill_booking_week") . "` where `jb_sn`='{$jb_sn}' and `jb_week`='{$jb_week}' and `jbt_sn` = '{$jbt_sn}'";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());

}
//取消預約
function delete_booking($jbt_sn = "", $jb_date = "", $jbi_sn = "")
{
    global $xoopsDB, $isAdmin;
    if (empty($jbt_sn) or empty($jb_date)) {
        return;
    }
    $bookingArr = get_bookingArr($jbt_sn, $jb_date);

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
    global $xoopsUser, $xoopsModuleConfig, $isAdmin;
    $can_booking = false;
    if ($xoopsUser) {
        if ($isAdmin) {
            return true;
            exit;
        }
        $needle_groups   = $xoopsUser->groups();
        $haystack_groups = $xoopsModuleConfig[$mode];
        //die(var_export($needle_groups)."==".var_export($haystack_groups));
        foreach ($needle_groups as $key => $group) {
            if (in_array($group, $haystack_groups)) {
                return true;
            }
        }
    }
    return false;
}
//檢查是否具有審核權限
function booking_approval($isAdmin = "")
{
    global $xoopsUser, $xoopsDB, $isAdmin;

    $can_approval = false;
    if ($xoopsUser) {
        if ($isAdmin) {
            return true;
            exit;
        }

        $uid = $xoopsUser->uid();

        $sql                = "select `jbi_approval` from `" . $xoopsDB->prefix("jill_booking_item") . "` where `jbi_approval` LIKE '%;{$uid}' || `jbi_approval` LIKE '{$uid};%' limit 1 ";
        $result             = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
        list($jbi_approval) = $xoopsDB->fetchRow($result);
        if (!empty($jbi_approval)) {
            return true;
            exit;
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
    $now             = 0;
    $end             = strtotime($end_date);
    $i               = 0;
    while ($now < $end) {
        $now = mktime(0, 0, 0, $m, $d + $i, $y);
        if (strpos($seed_weekday, strval(date('w', $now))) !== false) {
            $date_arr[] = date("Y-m-d", $now);
        }
        $i++;
    }
    return $date_arr;
}
