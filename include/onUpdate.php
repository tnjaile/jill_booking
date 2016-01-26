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
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL, 3, mysql_error());
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
    $xoopsDB->queryF($sql) or redirect_header(XOOPS_URL, 3, mysql_error());
    return true;
}
/*//建立目錄
function mk_dir($dir=""){
//若無目錄名稱秀出警告訊息
if(empty($dir))return;
//若目錄不存在的話建立目錄
if (!is_dir($dir)) {
umask(000);
//若建立失敗秀出警告訊息
mkdir($dir, 0777);
}
}

//拷貝目錄
function full_copy( $source="", $target=""){
if ( is_dir( $source ) ){
@mkdir( $target );
$d = dir( $source );
while ( FALSE !== ( $entry = $d->read() ) ){
if ( $entry == '.' || $entry == '..' ){
continue;
}

$Entry = $source . '/' . $entry;
if ( is_dir( $Entry ) ) {
full_copy( $Entry, $target . '/' . $entry );
continue;
}
copy( $Entry, $target . '/' . $entry );
}
$d->close();
}else{
copy( $source, $target );
}
}

function rename_win($oldfile,$newfile) {
if (!rename($oldfile,$newfile)) {
if (copy ($oldfile,$newfile)) {
unlink($oldfile);
return TRUE;
}
return FALSE;
}
return TRUE;
}

function delete_directory($dirname) {
if (is_dir($dirname))
$dir_handle = opendir($dirname);
if (!$dir_handle)
return false;
while($file = readdir($dir_handle)) {
if ($file != "." && $file != "..") {
if (!is_dir($dirname."/".$file))
unlink($dirname."/".$file);
else
delete_directory($dirname.'/'.$file);
}
}
closedir($dir_handle);
rmdir($dirname);
return true;
}
 */
