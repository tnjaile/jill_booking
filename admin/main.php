<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tnjaile 製作
// 製作日期：2015-01-23
// $Id:$
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = 'jill_booking_adm_main.html';
include_once "header.php";
include_once "../function.php";

/*-----------功能函數區--------------*/


//jill_booking_item編輯表單
function jill_booking_item_form($jbi_sn=""){
  global $xoopsDB , $xoopsTpl;

  //抓取預設值
  if(!empty($jbi_sn)){
    $DBV=get_jill_booking_item($jbi_sn);
  }else{
    $DBV=array();
  }

  //預設值設定
  $myts =& MyTextSanitizer::getInstance();

  //設定 jbi_sn 欄位的預設值
  $jbi_sn=!isset($DBV['jbi_sn'])?$jbi_sn:$DBV['jbi_sn'];
  $xoopsTpl->assign('jbi_sn' , $jbi_sn);

  //設定 jbi_start 欄位的預設值
  $jbi_start=!isset($DBV['jbi_start'])?date("Y-m-d"):$DBV['jbi_start'];
  $yesterday=date("Y-m-d", strtotime('-1 day'));
  $xoopsTpl->assign('jbi_start' , $jbi_start);
  $xoopsTpl->assign('yesterday' , $yesterday);

  //設定 jbi_end 欄位的預設值
  $jbi_end=!isset($DBV['jbi_end'])?"" :$DBV['jbi_end'];
  $xoopsTpl->assign('jbi_end' , $jbi_end);

  //設定 jbi_title 欄位的預設值
  $jbi_title=!isset($DBV['jbi_title'])?"":$DBV['jbi_title'];
  $xoopsTpl->assign('jbi_title' , $jbi_title);

  //設定 jbi_desc 欄位的預設值
  $jbi_desc=!isset($DBV['jbi_desc'])?"":$myts->displayTarea($DBV['jbi_desc'],$html=1, $smiley=1, $xcode=1, $image=1, $br=0);
  if(!file_exists(TADTOOLS_PATH."/ck.php")){
    redirect_header("index.php",3, _MD_NEED_TADTOOLS);
  }
  include_once TADTOOLS_PATH."/ck.php";
  $Editor=new CKEditor("jill_booking","jbi_desc",$jbi_desc);
  $Editor->setToolbarSet('myBasic');
  $Editor_code=$Editor->render();
  $xoopsTpl->assign('Editor_code' ,$Editor_code);

  //設定 jbi_approval 欄位的預設值
  $jbi_approval=!isset($DBV['jbi_approval'])?"0":$DBV['jbi_approval'];
  $xoopsTpl->assign('jbi_approval' , $jbi_approval);

  //設定 jbi_sort 欄位的預設值
  $jbi_sort=!isset($DBV['jbi_sort'])?jill_booking_item_max_sort():$DBV['jbi_sort'];
  $xoopsTpl->assign('jbi_sort' , $jbi_sort);

  //設定 jbi_enable 欄位的預設值
  $jbi_enable=!isset($DBV['jbi_enable'])?"1":$DBV['jbi_enable'];
  $xoopsTpl->assign('jbi_enable' , $jbi_enable);

  $op=(empty($jbi_sn))?"insert_jill_booking_item":"update_jill_booking_item";
  //$op="replace_jill_booking_item";

  //套用formValidator驗證機制
  if(!file_exists(TADTOOLS_PATH."/formValidator.php")){
    redirect_header("index.php",3, _TAD_NEED_TADTOOLS);
  }
  include_once TADTOOLS_PATH."/formValidator.php";
  $formValidator= new formValidator("#myForm",true);
  $formValidator_code=$formValidator->render();



  //加入Token安全機制
  include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
  $token = new XoopsFormHiddenToken();
  $token_form = $token->render();
  $xoopsTpl->assign("token_form" , $token_form);
  $xoopsTpl->assign('action' , $_SERVER["PHP_SELF"]);
  $xoopsTpl->assign('formValidator_code' , $formValidator_code);
  $xoopsTpl->assign('now_op' , 'jill_booking_item_form');
  $xoopsTpl->assign('next_op' , $op);

}


//自動取得jill_booking_item的最新排序
function jill_booking_item_max_sort(){
  global $xoopsDB;
  $sql = "select max(`jbi_sort`) from `".$xoopsDB->prefix("jill_booking_item")."`";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  list($sort)=$xoopsDB->fetchRow($result);
  return ++$sort;
}


//新增資料到jill_booking_item中
function insert_jill_booking_item(){
  global $xoopsDB,$xoopsUser;

	//取得使用者編號
	$uid=($xoopsUser)?$xoopsUser->uid():"";

  //XOOPS表單安全檢查
  if(!$GLOBALS['xoopsSecurity']->check()){
    $error=implode("<br />" , $GLOBALS['xoopsSecurity']->getErrors());
    redirect_header($_SERVER['PHP_SELF'],3, $error);
  }

  $myts =& MyTextSanitizer::getInstance();
  $_POST['jbi_start'] = $myts->addSlashes($_POST['jbi_start']);
  $_POST['jbi_end'] = $myts->addSlashes($_POST['jbi_end']);
  $_POST['jbi_title'] = $myts->addSlashes($_POST['jbi_title']);
  $_POST['jbi_desc'] = $myts->addSlashes($_POST['jbi_desc']);


  $sql = "insert into `".$xoopsDB->prefix("jill_booking_item")."`
  (`jbi_title`,`jbi_desc` , `jbi_sort` ,`jbi_start` , `jbi_end`, `jbi_enable`, `jbi_approval` )
  values( '{$_POST['jbi_title']}' , '{$_POST['jbi_desc']}', '{$_POST['jbi_sort']}'  , '{$_POST['jbi_start']}' , '{$_POST['jbi_end']}' , '{$_POST['jbi_enable']}','{$_POST['jbi_approval']}' )";
  $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

  //取得最後新增資料的流水編號
  $jbi_sn = $xoopsDB->getInsertId();


  return $jbi_sn;

}

//更新jill_booking_item某一筆資料
function update_jill_booking_item($jbi_sn=""){
  global $xoopsDB,$xoopsUser;

	//取得使用者編號
	$uid=($xoopsUser)?$xoopsUser->uid():"";

  //XOOPS表單安全檢查
  if(!$GLOBALS['xoopsSecurity']->check()){
    $error=implode("<br />" , $GLOBALS['xoopsSecurity']->getErrors());
    redirect_header($_SERVER['PHP_SELF'],3, $error);
  }

  $myts =& MyTextSanitizer::getInstance();
  $_POST['jbi_start'] = $myts->addSlashes($_POST['jbi_start']);
  $_POST['jbi_end'] = $myts->addSlashes($_POST['jbi_end']);
  $_POST['jbi_title'] = $myts->addSlashes($_POST['jbi_title']);
  $_POST['jbi_desc'] = $myts->addSlashes($_POST['jbi_desc']);


  $sql = "update `".$xoopsDB->prefix("jill_booking_item")."` set
   `jbi_title` = '{$_POST['jbi_title']}' ,
   `jbi_desc` = '{$_POST['jbi_desc']}' ,
   `jbi_sort` = '{$_POST['jbi_sort']}' ,
   `jbi_start` = '{$_POST['jbi_start']}' ,
   `jbi_end` = '{$_POST['jbi_end']}' ,
   `jbi_enable` = '{$_POST['jbi_enable']}',
   `jbi_approval` = '{$_POST['jbi_approval']}'
  where `jbi_sn` = '$jbi_sn'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());


  return $jbi_sn;
}

//刪除jill_booking_item某筆資料資料
function delete_jill_booking_item($jbi_sn=""){
  global $xoopsDB , $isAdmin;
  if(empty($jbi_sn))return;
  $sql = "delete from `".$xoopsDB->prefix("jill_booking_item")."` where `jbi_sn` = '{$jbi_sn}'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

}

//以流水號秀出某筆jill_booking_item資料內容
function show_one_jill_booking_item($jbi_sn=""){
  global $xoopsDB , $xoopsTpl , $isAdmin;

  if(empty($jbi_sn)){
    return;
  }else{
    $jbi_sn=intval($jbi_sn);
  }

  $myts =& MyTextSanitizer::getInstance();

  $sql = "select * from `".$xoopsDB->prefix("jill_booking_item")."` where `jbi_sn` = '{$jbi_sn}' ";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $all=$xoopsDB->fetchArray($result);

  //以下會產生這些變數： $jbi_sn , $jbi_start , $jbi_end , $jbi_title , $jbi_desc , $jbi_approval , $jbi_sort , $jbi_enable
  foreach($all as $k=>$v){
    $$k=$v;
  }


  //將是/否選項轉換為圖示
  $jbi_enable=($jbi_enable==1)? '<img src="../images/yes.gif" alt="'._YES.'" title="'._YES.'">' : '<img src="../images/no.gif" alt="'._NO.'" title="'._NO.'">';

  $jbi_approval=($jbi_approval==1)? '<img src="../images/yes.gif" alt="'._YES.'" title="'._YES.'">' : '<img src="../images/no.gif" alt="'._NO.'" title="'._NO.'">';
  //過濾讀出的變數值
  $jbi_start = $myts->htmlSpecialChars($jbi_start);
  $jbi_end = $myts->htmlSpecialChars($jbi_end);
  $jbi_title = $myts->htmlSpecialChars($jbi_title);
  $jbi_desc = $myts->displayTarea($jbi_desc, 1, 1, 1, 1, 0);

  $xoopsTpl->assign('jbi_sn' , $jbi_sn);
  $xoopsTpl->assign('jbi_start' , $jbi_start);
  $xoopsTpl->assign('jbi_end' , $jbi_end);
  $xoopsTpl->assign('jbi_title',$jbi_title);
  $xoopsTpl->assign('jbi_title_link',"<a href='{$_SERVER['PHP_SELF']}?jbi_sn={$jbi_sn}'>{$jbi_title}</a>");
  $xoopsTpl->assign('jbi_desc' , $jbi_desc);
  $xoopsTpl->assign('jbi_approval' , $jbi_approval);
  $xoopsTpl->assign('jbi_sort' , $jbi_sort);
  $xoopsTpl->assign('jbi_enable' , $jbi_enable);

  if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/sweet_alert.php")){
    redirect_header("index.php",3, _MA_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/sweet_alert.php";
  $sweet_alert=new sweet_alert();
  $delete_jill_booking_item_func=$sweet_alert->render('delete_jill_booking_item_func',"{$_SERVER['PHP_SELF']}?op=delete_jill_booking_item&jbi_sn=","jbi_sn");
  $xoopsTpl->assign('delete_jill_booking_item_func',$delete_jill_booking_item_func);

  $xoopsTpl->assign('action' , $_SERVER['PHP_SELF']);
  $xoopsTpl->assign('now_op' , 'show_one_jill_booking_item');
}


//列出所有jill_booking_item資料
function list_jill_booking_item(){
  global $xoopsDB , $xoopsTpl , $isAdmin;

  $myts =& MyTextSanitizer::getInstance();


  $sql = "select * from `".$xoopsDB->prefix("jill_booking_item")."` order by `jbi_sort`";

  //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
  $PageBar=getPageBar($sql,20,10,NULL,NULL,$_SESSION['bootstrap']);
  $bar=$PageBar['bar'];
  $sql=$PageBar['sql'];
  $total=$PageBar['total'];

  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

  $all_content="";
  $i=0;
  while($all=$xoopsDB->fetchArray($result)){
    //以下會產生這些變數： $jbi_sn , $jbi_start , $jbi_end , $jbi_title , $jbi_desc , $jbi_approval , $jbi_sort , $jbi_enable
    foreach($all as $k=>$v){
      $$k=$v;
    }

    //將是/否選項轉換為圖示
    $jbi_enable=($jbi_enable==1)? '<img src="../images/yes.gif" alt="'._YES.'" title="'._YES.'">' : '<img src="../images/no.gif" alt="'._NO.'" title="'._NO.'">';

    $jbi_approval=($jbi_approval==1)? '<img src="../images/yes.gif" alt="'._YES.'" title="'._YES.'">' : '<img src="../images/no.gif" alt="'._NO.'" title="'._NO.'">';

    //過濾讀出的變數值
    $jbi_start = $myts->htmlSpecialChars($jbi_start);
    $jbi_end = $myts->htmlSpecialChars($jbi_end);
    $jbi_title = $myts->htmlSpecialChars($jbi_title);
    $jbi_desc = $myts->displayTarea($jbi_desc, 1, 1, 1, 1, 0);

    $all_content[$i]['jbi_sn']=$jbi_sn;
    $all_content[$i]['jbi_start']=$jbi_start;
    $all_content[$i]['jbi_end']=$jbi_end;
    $all_content[$i]['jbi_title_link']="<a href='{$_SERVER['PHP_SELF']}?jbi_sn={$jbi_sn}'>{$jbi_title}</a>";
    $all_content[$i]['jbi_title']=$jbi_title;
    $all_content[$i]['jbi_desc']=$jbi_desc;
    $all_content[$i]['jbi_approval']=$jbi_approval;
    $all_content[$i]['jbi_sort']=$jbi_sort;
    $all_content[$i]['jbi_enable']=$jbi_enable;
    ++$i;
  }

  //刪除確認的JS

  $xoopsTpl->assign('bar' , $bar);
  $xoopsTpl->assign('action' , $_SERVER['PHP_SELF']);
  $xoopsTpl->assign('isAdmin' , $isAdmin);
  $xoopsTpl->assign('all_content' , $all_content);
  $xoopsTpl->assign('now_op' , 'list_jill_booking_item');


  if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/sweet_alert.php")){
    redirect_header("index.php",3, _MA_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/sweet_alert.php";
  $sweet_alert=new sweet_alert();
  $delete_jill_booking_item_func=$sweet_alert->render('delete_jill_booking_item_func',"{$_SERVER['PHP_SELF']}?op=delete_jill_booking_item&jbi_sn=","jbi_sn");
  $xoopsTpl->assign('delete_jill_booking_item_func',$delete_jill_booking_item_func);
}



/*-----------執行動作判斷區----------*/
$op=empty($_REQUEST['op'])?"":$_REQUEST['op'];
$jb_sn=empty($_REQUEST['jb_sn'])?"":intval($_REQUEST['jb_sn']);
$jbt_sn=empty($_REQUEST['jbt_sn'])?"":intval($_REQUEST['jbt_sn']);
$jbi_sn=empty($_REQUEST['jbi_sn'])?"":intval($_REQUEST['jbi_sn']);

switch($op){
  /*---判斷動作請貼在下方---*/

  //新增資料
  case "insert_jill_booking_item":
  $jbi_sn=insert_jill_booking_item();
  header("location: {$_SERVER['PHP_SELF']}?jbi_sn=$jbi_sn");
  break;

  //更新資料
  case "update_jill_booking_item":
  update_jill_booking_item($jbi_sn);
  header("location: {$_SERVER['PHP_SELF']}?jbi_sn=$jbi_sn");
  break;


  case "jill_booking_item_form":
  jill_booking_item_form($jbi_sn);
  break;


  case "delete_jill_booking_item":
  delete_jill_booking_item($jbi_sn);
  header("location: {$_SERVER['PHP_SELF']}");
  break;



  default:
  if(empty($jbi_sn)){
    list_jill_booking_item();
  }else{
    show_one_jill_booking_item($jbi_sn);
  }
  break;


  /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign("isAdmin" , true);
include_once 'footer.php';
?>