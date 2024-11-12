<?php
use XoopsModules\Tadtools\Utility;
include "../../../mainfile.php";
include '../../../include/cp_header.php';
$xoopsLogger->activated = false;

$sort = 1;
foreach ($_POST['tr'] as $jbt_sn) {
    $sql = 'UPDATE `' . $xoopsDB->prefix('jill_booking_time') . '` SET `jbt_sort`=? WHERE `jbt_sn`=?';
    Utility::query($sql, 'ii', [$sort, $jbt_sn]) or die(_MA_UPDATE_FAILED . ' (' . date('Y-m-d H:i:s') . ')' . $sql);
    $sort++;
}

echo _MA_UPDATE_COMPLETED . " (" . date("Y-m-d H:i:s") . ")";
