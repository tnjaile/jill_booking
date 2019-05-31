<?php
use XoopsModules\Jill_booking\Update;
if (!class_exists('XoopsModules\Jill_booking\Update')) {
    include dirname(__DIR__) . '/preloads/autoloader.php';
}
function xoops_module_update_jill_booking(&$module, $old_version)
{
    // die(print_r(get_declared_classes()));
    if (Update::chk_chk1()) {
        Update::go_update1();
    }

    if (Update::chk_chk2()) {
        Update::go_update2();
    }
    if (Update::chk_chk3()) {
        Update::go_update3();
    }
    if (Update::chk_chk4()) {
        Update::go_update4();
    }
    return true;
}
