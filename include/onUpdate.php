<?php

function xoops_module_update_jill_booking(&$module, $old_version)
{
    global $xoopsDB;

    if (chk_chk1()) {
        go_update1();
    }
    if (chk_chk2()) {
        go_update2();
    }
    if (chk_chk3()) {
        go_update3();
    }
    return true;
}

//檢查jbt_sn欄位 自動遞增是否存在
function chk_chk1()
{
    global $xoopsDB;
    $sql    = "show columns from " . $xoopsDB->prefix("jill_booking_date") . " where Extra='auto_increment' ";
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

//修正jbt_sn欄位，取消自動遞增
function go_update1()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("jill_booking_date") . " CHANGE `jbt_sn` `jbt_sn` mediumint(8) unsigned NOT NULL COMMENT '時段編號' AFTER `jb_date` ";
    $xoopsDB->queryF($sql) or web_error($sql);
    return true;
}
//檢查jbi_approval 欄位 enum('1','0')是否存在
function chk_chk2()
{
    global $xoopsDB;
    $sql    = "show columns from " . $xoopsDB->prefix("jill_booking_item") . " where Field='jbi_approval' && Type='enum(\'0\',\'1\')' ";
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return false;
    }

    return true;
}

//修正jbi_approval欄位，改為varchar(255)
function go_update2()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("jill_booking_item") . " CHANGE `jbi_approval` `jbi_approval` varchar(255) COLLATE 'utf8_general_ci' NOT NULL COMMENT '審核人員' AFTER `jbi_enable` ";
    $xoopsDB->queryF($sql) or web_error($sql);
    return true;
}
//檢查有無通過日期欄位
function chk_chk3()
{
    global $xoopsDB;
    $sql    = "select `pass_date`  from " . $xoopsDB->prefix("jill_booking_date");
    $result = $xoopsDB->query($sql);
    if (empty($result)) {
        return true;
    }

    return false;
}
//執行更新通過日期欄位
function go_update3()
{
    global $xoopsDB;
    $sql = "ALTER TABLE " . $xoopsDB->prefix("jill_booking_date") . " ADD `approver` mediumint(8) unsigned NOT NULL default 0,ADD `pass_date` date NOT NULL ";
    $xoopsDB->queryF($sql) or web_error($sql);

    //正確抓取XOOPS時間
    $now = date('Y-m-d', xoops_getUserTimestamp(time()));
    //1.今天以前的2.今天起以後的，若jb_staus=1 3.今天起以後的，若jb_staus=0，節次相同，更新日期
    $sql = "update " . $xoopsDB->prefix("jill_booking_date") . " set `pass_date`='{$now}' where jb_date<'{$now}' || (`jb_status`='1' && jb_date>='{$now}')";
    $xoopsDB->queryF($sql) or web_error($sql);

    $sql = "select  `jb_date`, `jbt_sn`  from " . $xoopsDB->prefix("jill_booking_date") . " where
            `jb_status`='1' && jb_date>='{$now}'";
    $result = $xoopsDB->queryF($sql) or web_error($sql);
    while (list($jb_date, $jbt_sn) = $xoopsDB->fetchRow($result)) {
        $sql2 = "update " . $xoopsDB->prefix("jill_booking_date") . " set `pass_date`='{$now}' where  `jb_date`='{$jb_date}' and `jbt_sn`='{$jbt_sn}'";
        $xoopsDB->queryF($sql2) or web_error($sql2);
    }
    return true;
}
