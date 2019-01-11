<?php
include "../../../mainfile.php";
include XOOPS_ROOT_PATH . "/modules/tadtools/tad_function.php";

$sort = 1;
foreach ($_POST['tr'] as $jbt_sn) {
    $sql = "update " . $xoopsDB->prefix("jill_booking_time") . " set `jbt_sort`='{$sort}' where `jbt_sn`='{$jbt_sn}'";
    $xoopsDB->queryF($sql) or die(_TAD_SORT_FAIL . " (" . date("Y-m-d H:i:s") . ")" . $sql);
    $sort++;
}

echo _TAD_SORTED . " (" . date("Y-m-d H:i:s") . ")";
