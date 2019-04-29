<?php
include "../../../mainfile.php";

$sort = 1;
foreach ($_POST['tr'] as $jbt_sn) {
    $sql = "update " . $xoopsDB->prefix("jill_booking_time") . " set `jbt_sort`='{$sort}' where `jbt_sn`='{$jbt_sn}'";
    $xoopsDB->queryF($sql) or die(_MD_UPDATE_FAILED . " (" . date("Y-m-d H:i:s") . ")" . $sql);
    $sort++;
}

echo _MD_UPDATE_COMPLETED . " (" . date("Y-m-d H:i:s") . ")";
