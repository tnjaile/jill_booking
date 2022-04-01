<?php
include '../../../mainfile.php';
include '../../../include/cp_header.php';

$sort = 1;
foreach ($_POST['tr'] as $jbt_sn) {
    $sql = 'update ' . $xoopsDB->prefix('jill_booking_time') . " set `jbt_sort`='{$sort}' where `jbt_sn`='{$jbt_sn}'";
    $xoopsDB->queryF($sql) or die(_MA_UPDATE_FAILED . ' (' . date('Y-m-d H:i:s') . ')' . $sql);
    $sort++;
}

echo _MA_UPDATE_COMPLETED . ' (' . date('Y-m-d H:i:s') . ')';
