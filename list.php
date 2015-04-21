<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tnjaile 製作
// 製作日期：2015-01-23
// $Id:$
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
include "header.php";
$xoopsOption['template_main'] = set_bootstrap("jill_booking_list.html");
include_once XOOPS_ROOT_PATH."/header.php";

/*-----------功能函數區--------------*/
//列出所有jill_booking資料
function jill_booking_list(){
  global $xoopsDB , $xoopsTpl ,$xoopsUser, $isAdmin;
  if(!$xoopsUser)return;
  $uid=$xoopsUser->uid();
  $myts =& MyTextSanitizer::getInstance();

  $sql = "select * from `".$xoopsDB->prefix("jill_booking")."` where `jb_uid`='{$uid}' order by `jb_booking_time` desc ";
  //die($sql);
  //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
  $PageBar=getPageBar($sql,20,10,NULL,NULL,$_SESSION['bootstrap']);
  $bar=$PageBar['bar'];
  $sql=$PageBar['sql'];
  $total=$PageBar['total'];

  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());


  $all_content="";
  $i=0;
  while($all=$xoopsDB->fetchArray($result)){
    //以下會產生這些變數： $jb_sn , $jb_booking_content , $jb_start_date , $jb_end_date , $jb_week , $jb_uid , $jb_booking_time , $jbt_sn , $jb_status
    foreach($all as $k=>$v){
      $$k=$v;
    }

    //過濾讀出的變數值
    $jb_booking_content = $myts->displayTarea($jb_booking_content, 1, 1, 0, 1, 0);
    $jb_start_date = $myts->htmlSpecialChars($jb_start_date);
    $jb_end_date = $myts->htmlSpecialChars($jb_end_date);

    $all_content[$i]['jb_sn']=$jb_sn;
    $all_content[$i]['jb_uid']=$jb_uid;
    $all_content[$i]['jb_booking_time']=$jb_booking_time;
    $all_content[$i]['jb_booking_content']=$jb_booking_content;
    $all_content[$i]['jb_start_date']=$jb_start_date;
    $all_content[$i]['jb_end_date']=$jb_end_date;
    $i++;
  }

  //刪除確認的JS

  $xoopsTpl->assign('bar' , $bar);
  $xoopsTpl->assign('action' , $_SERVER['PHP_SELF']);
  $xoopsTpl->assign('isAdmin' , $isAdmin);
  $xoopsTpl->assign('all_content' , $all_content);
  $xoopsTpl->assign('now_op' , 'jill_booking_list');


  if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/sweet_alert.php")){
    redirect_header("index.php",3, _MD_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/sweet_alert.php";
  $sweet_alert=new sweet_alert();
  $delete_jill_booking_func=$sweet_alert->render('delete_jill_booking_func',"{$_SERVER['PHP_SELF']}?op=delete_jill_booking&jb_sn=","jb_sn");
  $xoopsTpl->assign('delete_jill_booking_func',$delete_jill_booking_func);


}
//刪除預約紀錄
function delete_bookinglist($jb_sn=""){
  global $xoopsUser,$xoopsDB;
  if(empty($jb_sn))return;
  $bookingArr=get_jill_booking($jb_sn);
  //die(var_export($bookingArr));
  $uid=$xoopsUser->uid();
  if($uid==$bookingArr['jb_uid']){
    //刪除jill_booking_date、jill_booking、jill_booking_week
    $sql = "delete from `".$xoopsDB->prefix("jill_booking_date")."` where `jb_sn` = '{$jb_sn}'";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

    $sql = "delete from `".$xoopsDB->prefix("jill_booking_week")."` where `jb_sn` = '{$jb_sn}'";
    $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

    delete_jill_booking($jb_sn);
  }

}

/*-----------執行動作判斷區----------*/
$op=empty($_REQUEST['op'])?"":$_REQUEST['op'];
$jb_sn=empty($_REQUEST['jb_sn'])?"":intval($_REQUEST['jb_sn']);



switch($op){
  /*---判斷動作請貼在下方---*/
 case "delete_jill_booking":

  delete_bookinglist($jb_sn);
  header("location: {$_SERVER['PHP_SELF']}");
  exit;
  break;


  default:
    jill_booking_list();
  break;


  /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign( "toolbar" , toolbar_bootstrap($interface_menu)) ;
$xoopsTpl->assign( "isAdmin" , $isAdmin) ;
$xoopsTpl->assign( "can_booking" , $can_booking);
$xoopsTpl->assign( "can_cancel" , $can_cancel);
include_once XOOPS_ROOT_PATH.'/footer.php';
?>