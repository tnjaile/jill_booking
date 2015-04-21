<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tnjaile 製作
// 製作日期：2015-01-23
// $Id:$
// ------------------------------------------------------------------------- //

include_once "../../mainfile.php";
include_once "function.php";
$can_booking=booking_perm("booking_group");
$can_cancel=booking_perm("can_cancel");

//判斷是否對該模組有管理權限
$isAdmin=false;

$interface_menu[_MD_JILLBOOKIN_SMNAME1]="index.php";
$interface_menu[_MD_JILLBOOKIN_SMNAME2]="batch.php";

if ($xoopsUser) {
  $module_id = $xoopsModule->getVar('mid');
  $isAdmin=$xoopsUser->isAdmin($module_id);
  $interface_menu[_MD_JILLBOOKIN_SMNAME3]="list.php";
}


if($isAdmin){
  $interface_menu[_TAD_TO_ADMIN]="admin/main.php";
}
?>