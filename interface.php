<?php
//判斷是否對該模組有管理權限
$_SESSION['can_booking'] = false;
$_SESSION['Isapproval'] = false;
$interface_menu[_MD_JILLBOOKIN_SMNAME1] = "index.php";
$interface_icon[_MD_JILLBOOKIN_SMNAME1] = "fa-calendar-plus";

//判斷是否對該模組有管理權限
if (!isset($jill_book_adm)) {
    $jill_book_adm = ($xoopsUser) ? $xoopsUser->isAdmin() : false;
}

if ($xoopsUser) {
    $_SESSION['Isapproval'] = booking_approval($jill_book_adm);
    $_SESSION['can_booking'] = booking_perm("booking_group");
    if ($_SESSION['Isapproval'] or $_SERVER['PHP_SELF'] == '/admin.php') {
        $interface_menu[_MD_JILLBOOKIN_SMNAME4] = "listapproval.php";
        $interface_icon[_MD_JILLBOOKIN_SMNAME4] = "fa-check-square";

    }
    if ($_SESSION['can_booking'] or $_SERVER['PHP_SELF'] == '/admin.php') {
        $interface_menu[_MD_JILLBOOKIN_SMNAME2] = "batch.php";
        $interface_icon[_MD_JILLBOOKIN_SMNAME2] = "fa-address-book";

        //個人預約清單
        $interface_menu[_MD_JILLBOOKIN_SMNAME3] = "list.php";
        $interface_icon[_MD_JILLBOOKIN_SMNAME3] = "fa-id-card";

    }
}
