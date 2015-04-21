<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tnjaile 製作
// 製作日期：2015-01-14
// $Id:$
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = 'jill_booking_adm_time.html';
include_once "header.php";
include_once "../function.php";

/*-----------功能函數區--------------*/



//自動取得jill_booking_time的最新排序
function jill_booking_time_max_sort($jbi_sn=""){
  global $xoopsDB;
  $sql = "select max(`jbt_sort`) from `".$xoopsDB->prefix("jill_booking_time")."` where jbi_sn=$jbi_sn ";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  list($sort)=$xoopsDB->fetchRow($result);
  return ++$sort;
}





//新增資料到jill_booking_time中
function insert_jill_booking_time(){
  global $xoopsDB;

  $myts =& MyTextSanitizer::getInstance();
  $_POST['jbt_title'] = $myts->addSlashes($_POST['jbt_title']);
  $week=implode(",",$_POST['jbt_week']);

  $jbt_sort=jill_booking_time_max_sort($_POST['jbi_sn']);
  $sql = "insert into `".$xoopsDB->prefix("jill_booking_time")."`
  (`jbi_sn` , `jbt_title` , `jbt_sort`,`jbt_week`)
  values('{$_POST['jbi_sn']}' , '{$_POST['jbt_title']}' , '{$jbt_sort}','{$week}')";
  $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

  //取得最後新增資料的流水編號
  $jbt_sn = $xoopsDB->getInsertId();
  return $_POST['jbi_sn'];
}


//刪除jill_booking_time某筆資料資料
function delete_jill_booking_time($jbt_sn=""){
  global $xoopsDB , $isAdmin;
  if(empty($jbt_sn))return;
  $sql = "delete from `".$xoopsDB->prefix("jill_booking_time")."` where `jbt_sn` = '{$jbt_sn}'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
}

//列出所有jill_booking_time資料
function list_jill_booking_time($jbi_sn=""){
  global $xoopsDB , $xoopsTpl , $isAdmin;
  if(empty($jbi_sn))return;

  $item=get_jill_booking_item($jbi_sn);

  include_once XOOPS_ROOT_PATH."/modules/tadtools/jeditable.php";
  $jeditable = new jeditable();

  $myts =& MyTextSanitizer::getInstance();
  $sql = "select * from `".$xoopsDB->prefix("jill_booking_time")."`
          where `jbi_sn`='$jbi_sn' order by `jbt_sort`";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $total=$xoopsDB->getRowsNum($result);
  $all_content="";
  $i=0;
  while($all=$xoopsDB->fetchArray($result)){
    //以下會產生這些變數： $jbt_sn , $jbi_sn , $jbt_title , $jbt_sort
    foreach($all as $k=>$v){
      $$k=$v;
    }

    //過濾讀出的變數值
    $jbt_title = $myts->htmlSpecialChars($jbt_title);

    $jeditable->setTextCol("#jbt_title_{$jbt_sn}", 'time.php','100%','11pt',"{'jbt_sn':$jbt_sn,'op' : 'save_jbt_title'}" , _TAD_EDIT._MA_JILLBOOKIN_JBT_TITLE);

    $all_content[$i]['jbi_sn']=$jbi_sn;
    $all_content[$i]['jbt_sn']=$jbt_sn;
    $all_content[$i]['jbt_title_link']="<a href='{$_SERVER['PHP_SELF']}?jbt_sn={$jbt_sn}'>{$jbt_title}</a>";
    $all_content[$i]['jbt_title']=$jbt_title;
    $all_content[$i]['jbt_sort']=$jbt_sort;
    $all_content[$i]['jbt_week']=strval($jbt_week);
    $booking_times=get_booking_times($jbt_sn);
    $all_content[$i]['booking_times']=empty($booking_times)?"":sprintf(_MA_JILLBOOKIN_BOOKING_TIME,$booking_times);
    $w_arr=explode(',',$jbt_week);
    for($j=0;$j<=7;$j++){
      $name="w{$j}";
      $pic=in_array($j, $w_arr)?"yes.gif":"no.gif";

      $all_content[$i][$name]="<img src='../images/{$pic}' id='{$jbt_sn}_{$j}' onClick=\"change_enable($jbt_sn,$j);\" style='cursor: pointer;'>";
    }
    ++$i;
  }

  //die(var_export($all_content));
  //刪除確認的JS

  $xoopsTpl->assign('item' , $item);
  $xoopsTpl->assign('bar' , $bar);
  $xoopsTpl->assign('action' , $_SERVER['PHP_SELF']);
  $xoopsTpl->assign('isAdmin' , $isAdmin);
  $xoopsTpl->assign('all_content' , $all_content);
  $xoopsTpl->assign('now_op' , 'list_jill_booking_time');
  $xoopsTpl->assign('jbi_sn' , $jbi_sn);

  if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/sweet_alert.php")){
    redirect_header("index.php",3, _MA_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/sweet_alert.php";
  $sweet_alert=new sweet_alert();
  $delete_jill_booking_time_func=$sweet_alert->render('delete_jill_booking_time_func',"{$_SERVER['PHP_SELF']}?op=delete_jill_booking_time&jbi_sn=$jbi_sn&jbt_sn=","jbt_sn");
  $xoopsTpl->assign('delete_jill_booking_time_func',$delete_jill_booking_time_func);

  //套用formValidator驗證機制
  if(!file_exists(TADTOOLS_PATH."/formValidator.php")){
    redirect_header("index.php",3, _TAD_NEED_TADTOOLS);
  }
  include_once TADTOOLS_PATH."/formValidator.php";
  $formValidator= new formValidator("#myForm",true);
  $formValidator_code=$formValidator->render();
  $xoopsTpl->assign('formValidator_code',$formValidator_code);

  $jeditable_set=$jeditable->render();
  $xoopsTpl->assign('jeditable_set',$jeditable_set);

  //找出現有場地
  $i=0;
  $place_time="";
  $sql = "select a.* , count(b.jbt_sn) as counter from `".$xoopsDB->prefix("jill_booking_item")."` as a join `".$xoopsDB->prefix("jill_booking_time")."` as b on a.jbi_sn=b.jbi_sn where a.jbi_enable='1' group by b.jbi_sn";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  while($data=$xoopsDB->fetchArray($result)){
    $data['jbi_link']=sprintf(_MA_JILLBOOKIN_IMPORT_PLACE,$data['jbi_title'],$data['counter']);
    $place_time[$i]=$data;
    $i++;
  }
  $xoopsTpl->assign('place_time',$place_time);
  $xoopsTpl->assign('jquery', get_jquery(true));

}

//取得該節被預約的次數
function get_booking_times($jbt_sn=""){
  global $xoopsDB;

  $sql = "select count(*) from ".$xoopsDB->prefix("jill_booking_date")." where jbt_sn='{$jbt_sn}' ";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  list($all)=$xoopsDB->fetchRow($result);
  return $all;
}

//複製時間區段
function copy_time($jbi_sn="",$to_jbi_sn=""){
  global $xoopsDB;

  $sql = "select * from ".$xoopsDB->prefix("jill_booking_time")." where jbi_sn='{$jbi_sn}' ";

  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  while($all=$xoopsDB->fetchArray($result)){
    //以下會產生這些變數： $jbt_sn,$jbi_sn,$jbt_title,$jbt_sort
    foreach($all as $k=>$v){
      $$k=$v;
    }

    $ins[]="('{$to_jbi_sn}' , '{$jbt_title}' , '{$jbt_sort}','{$jbt_week}')";
  }

  $ins_sql=implode(",",$ins);

  $sql = "insert into ".$xoopsDB->prefix("jill_booking_time")."
  (`jbi_sn` , `jbt_title` , `jbt_sort`,`jbt_week`)
  values{$ins_sql}";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
}



//從範本快速匯入時段設定
function import_time($jbi_sn="",$type=""){
  global $xoopsDB,$xoopsTpl;
  if($type=='18'){
    for($i=1;$i<=8;$i++){
      $jbt_title=sprintf(_MA_JILLBOOKIN_N_TIME,$i);
      $sql = "insert into ".$xoopsDB->prefix("jill_booking_time")."
      (`jbi_sn` , `jbt_title` , `jbt_sort`,`jbt_week`)
      values('{$jbi_sn}' , '{$jbt_title}' , $i , '1,2,3,4,5')";
      $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    }
  }
  elseif($type=='apm'){
    $apm_arr[1]=_MA_JILLBOOKIN_AM;
    $apm_arr[2]=_MA_JILLBOOKIN_PM;
   for($i=1;$i<=2;$i++){
     $jbt_title=$apm_arr[$i];
     $sql = "insert into ".$xoopsDB->prefix("jill_booking_time")."
     (`jbi_sn` , `jbt_title` , `jbt_sort`,`jbt_week`)
     values('{$jbi_sn}' , '{$jbt_title}' , $i , '1,2,3,4,5')";
     $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
   }
  }
}

//改變啟用狀態
function change_enable($jbt_sn="",$week=""){
  global $xoopsDB,$xoopsTpl;
  $time=get_jill_booking_time($jbt_sn);
  $jbt_week=strval($time['jbt_week']);
  $week_arr=explode(',', $jbt_week);
  if(in_array($week, $week_arr)){
    foreach($week_arr as $w){
      if($w!=$week){
        $new_week[]=$w;
      }
    }
    $new_week=implode(',',$new_week);
    $new_pic= "../images/no.gif";
  }else{
    $week_arr[]=$week;
    sort($week_arr);
    $new_week=implode(',',$week_arr);
    $new_pic= "../images/yes.gif";
  }

  $sql = "update ".$xoopsDB->prefix("jill_booking_time")." set `jbt_week`='{$new_week}' where jbt_sn='{$jbt_sn}'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  return $new_pic;
}

function save_jbt_title($jbt_sn){
  global $xoopsDB,$xoopsTpl;
  $myts =& MyTextSanitizer::getInstance();
  $jbt_title = $myts->addSlashes($_POST['value']);
  $sql = "update `".$xoopsDB->prefix("jill_booking_time")."` set `jbt_title` = '{$jbt_title}'
  where `jbt_sn` = '$jbt_sn'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
}

/*-----------執行動作判斷區----------*/
$op=empty($_REQUEST['op'])?"":$_REQUEST['op'];
$jb_sn=empty($_REQUEST['jb_sn'])?"":intval($_REQUEST['jb_sn']);
$jbt_sn=empty($_REQUEST['jbt_sn'])?"":intval($_REQUEST['jbt_sn']);
$jbi_sn=empty($_REQUEST['jbi_sn'])?"":intval($_REQUEST['jbi_sn']);
$to_jbi_sn=empty($_REQUEST['to_jbi_sn'])?"":intval($_REQUEST['to_jbi_sn']);
$jbt_sn=empty($_REQUEST['jbt_sn'])?"":intval($_REQUEST['jbt_sn']);


switch($op){
  /*---判斷動作請貼在下方---*/

  //新增資料
  case "insert_jill_booking_time":
  $jbi_sn=insert_jill_booking_time();
  header("location: {$_SERVER['PHP_SELF']}?jbi_sn=$jbi_sn");
  break;

  case "delete_jill_booking_time":
  delete_jill_booking_time($jbt_sn);
  header("location: {$_SERVER['PHP_SELF']}?jbi_sn=$jbi_sn");
  break;


  case "copy_time":
  copy_time($jbi_sn,$to_jbi_sn);
  header("location: {$_SERVER['PHP_SELF']}?op=list_jill_booking_time&jbi_sn={$to_jbi_sn}");
  break;

  case "import_time":
  import_time($jbi_sn,$_GET['type']);
  header("location: {$_SERVER['PHP_SELF']}?op=list_jill_booking_time&jbi_sn={$jbi_sn}");
  break;


  case "change_enable":
  $new_pic=change_enable($jbt_sn,$_POST['week']);
  die($new_pic);
  break;

  case "save_jbt_title":
  save_jbt_title($jbt_sn);
  die($_POST['value']);
  break;


  default:
  list_jill_booking_time($jbi_sn);
  break;


  /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign("isAdmin" , true);
include_once 'footer.php';
?>