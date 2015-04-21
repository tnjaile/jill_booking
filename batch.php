<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tnjaile 製作
// 製作日期：2015-01-23
// $Id:$
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
include "header.php";
$xoopsOption['template_main'] = set_bootstrap("jill_booking_batch.html");
include_once XOOPS_ROOT_PATH."/header.php";

/*-----------功能函數區--------------*/

//jill_booking編輯表單
function jill_booking_form($jbi_sn=""){
  global $xoopsDB , $xoopsTpl;
  //場地設定
  $item_opt=get_jill_booking_time_options($jbi_sn);

  if(!empty($jbi_sn)){
    //場地資訊
    $itemArr=get_jill_booking_item($jbi_sn,1);
    $xoopsTpl->assign('itemArr' ,$itemArr);
    // array ('jbi_sn' => '2','jbi_title' =>'多功能教室','jbi_desc' => '<p>多功能教室多功能教室</p>','jbi_sort' => '1','jbi_start' => '2015-01-28','jbi_end' => '0000-00-00','jbi_enable' => '1','jbi_approval' => '0',)
    //die(var_export($itemArr));

    //預設值設定
    //設定 jb_booking_content 欄位的預設值
    $xoopsTpl->assign('jb_booking_content' ,"");

    //設定 jb_start_date 欄位的預設值
    $jb_start_date=(strtotime($itemArr['jbi_start'])<=strtotime(date("Y-m-d")))?date("Y-m-d"):$itemArr['jbi_start'];
    $xoopsTpl->assign('jb_start_date' ,$jb_start_date );
    //設定 jb_end_date 欄位的預設值
    $xoopsTpl->assign('jb_end_date' ,$jb_start_date);
    $end=empty($itemArr['jbi_end'])?0:strtotime($itemArr['jbi_end']);
    $max_date=($end==0)?'':$itemArr['jbi_end'];
    $xoopsTpl->assign('max_date' ,$max_date);

    //時段資訊
    $timeArr=get_bookingtime_jbisn($jbi_sn);
    $xoopsTpl->assign('timeArr' ,$timeArr);
    //die(var_export($timeArr));
    $weektime="";
    foreach ($timeArr as $t => $time) {
      for ($w=0; $w <7 ; $w++) {
        $jbt_sn=$time['jbt_sn'];
        $jbt_week=strval($time['jbt_week']);
        $weektime[$t][$w]['jbt_week']=(strpos($jbt_week,strval($w))!==false)?"<input type='checkbox' name='jb_week[$jbt_sn][]'  value='$w' >":"<span style='color:#D44950'><i class='fa fa-times'></i></span>";
      }
    }
    //die(var_export($weektime));
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
    $xoopsTpl->assign('formValidator_code' , $formValidator_code);
    $xoopsTpl->assign('weektime' , $weektime);
    $xoopsTpl->assign('next_op' , "insert_jill_booking");
  }
  $xoopsTpl->assign('item_opt' , $item_opt);
  $xoopsTpl->assign('now_op' , 'jill_booking_form');
  $xoopsTpl->assign('action' , $_SERVER["PHP_SELF"]);
}
//批次寫入
function bath_insert(){
  //XOOPS表單安全檢查
  if(!$GLOBALS['xoopsSecurity']->check()){
    $error=implode("<br />" , $GLOBALS['xoopsSecurity']->getErrors());
    redirect_header($_SERVER['PHP_SELF'],3, $error);
  }
  $jb_sn=insert_jill_booking($jbt_sn);
  foreach ($_POST['jb_week'] as $jbt_sn => $jb_week) {
    insert_jill_booking_week($jb_sn,$jbt_sn,"",$jb_week);
  }
  $sn['jb_sn']=$jb_sn;
  $sn['jbi_sn']=$_POST['jbi_sn'];
  return $sn;
}
//列出所有批次預約表單
function list_jill_booking($jb_sn="",$jbi_sn=""){
  global $xoopsDB , $xoopsTpl;
  if(empty($jb_sn))return;
  //取得jill_booking
  $DBV1=get_jill_booking($jb_sn);
  $DBV2=get_booking_weekArr($jb_sn);
  //die(var_export($DBV2['jbt_sn']));
  //可啟用場地資訊
  $itemArr=get_jill_booking_item($jbi_sn,1);
  //die(var_export($itemArr));
  $xoopsTpl->assign("jbi_title" ,$itemArr['jbi_title']);
  $xoopsTpl->assign("jbi_sn" ,$jbi_sn);

  $dateweek="";
  $i=0;
  $str_jbt_sn=implode(",",$DBV2['jbt_sn']);
  $maxwaiting=get_maxwaiting_byrange($DBV1['jb_start_date'],$DBV1['jb_end_date'],$str_jbt_sn);
  //die("ads".$maxwaiting);
  $xoopsTpl->assign("maxwaiting" , $maxwaiting);
  foreach ($DBV2 as $k => $jb_weekArr) {
    //die($jb_weekArr['jb_week']);
    //$jb_weekArr['jbt_sn'];
    $dateArr=getdateArr($jb_weekArr['jb_week'],$DBV1['jb_start_date'],$DBV1['jb_end_date']);
    $timeArr=get_jill_booking_time($jb_weekArr['jbt_sn']);
    $time[]=$jbt_sn;
    foreach ($dateArr as $date) {
      $dateweek[$i]['jb_date']=$date;
      $dateweek[$i]['week']=date('w',strtotime($date));
      $dateweek[$i]['jbt_title']=$timeArr['jbt_title'];
      $dateweek[$i]['jbt_sn']=$jb_weekArr['jbt_sn'];
      if(!empty($maxwaiting)){
        $waitingArr=get_jbwaiting($jb_weekArr['jbt_sn'],$date);
        for ($j=0; $j <$maxwaiting ; $j++) {
          $ok="<span style='color:#D44950'><i class='fa fa-check'></i></span>";
          $jb_exit=0;
          if(!empty($waitingArr)){
            foreach ($waitingArr as $key => $w) {
              if($w['jb_waiting']==$j+1){
                $ok=$w['name'];
              }
              $jb_exit=1;
            }
            $dateweek[$i]['waitingArr'][$j]['name']=$ok;
            $dateweek[$i]['jb_exit']=$jb_exit;
          }
          else{
            $dateweek[$i]['waitingArr'][0]['name']="<span style='color:#D44950'><i class='fa fa-check'></i></span>";
            for ($j=1; $j <$maxwaiting ; $j++) {
              $dateweek[$i]['waitingArr'][$j]['name']="";
            }
            $dateweek[$i]['jb_exit']=0;

          }
        }
      }
      else{
        $dateweek[$i]['waitingArr'][0]['name']="<span style='color:#D44950'><i class='fa fa-check'></i></span>";
        $dateweek[$i]['jb_exit']=0;
      }

      ++$i;
    }
  }

  foreach ($dateweek as $key => $value) {
    $jb_date[$key] = strtotime($value['jb_date']);
  }
  array_multisort($jb_date, $dateweek);
  //die(var_export($dateweek));
  $xoopsTpl->assign("jb_sn" , $jb_sn);
  $xoopsTpl->assign("dateweek" , $dateweek);
  $xoopsTpl->assign('now_op' , 'list_jill_booking');
  $xoopsTpl->assign('action' , $_SERVER["PHP_SELF"]);
  $xoopsTpl->assign('next_op' , "insert_jill_booking_date");
}
//依日期範圍及節次取得jill_booking_date的資訊
function get_jbwaiting($jbt_sn="",$jb_date=""){
  global $xoopsDB;
  $sql = "select a.jb_waiting,b.jb_uid from `".$xoopsDB->prefix("jill_booking_date")."` as a
          join `".$xoopsDB->prefix("jill_booking")."` as b on a.jb_sn=b.jb_sn
          where a.jbt_sn=$jbt_sn and a.jb_date='{$jb_date}' order by a.jb_waiting ";
  //die($sql);
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $data="";
  $i=0;
  while(list($jb_waiting,$jb_uid)= $xoopsDB->fetchRow($result)){
    $data[$i]['jb_waiting'] = $jb_waiting;
    $data[$i]['name'] = XoopsUser::getUnameFromId($jb_uid,1);
    ++$i;
  }
  return $data;
}
//依日期範圍取得jill_booking候補值
function get_maxwaiting_byrange($jb_start_date="",$jb_end_date="",$str=""){
  global $xoopsDB;
  //die($str);
  $sql = "select max(`jb_waiting`) from `".$xoopsDB->prefix("jill_booking_date")."` where `jbt_sn` in({$str}) and (`jb_date` between '{$jb_start_date}' and '{$jb_end_date}' ) ";
  //die($sql);
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  list($max)=$xoopsDB->fetchRow($result);
  return $max;
}

//以流水號取得某筆jill_booking_week資料
function get_booking_weekArr($jb_sn=""){
  global $xoopsDB;
  if(empty($jb_sn))return;
  $sql = "select * from `".$xoopsDB->prefix("jill_booking_week")."` where `jb_sn` = '{$jb_sn}'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $data="";
  $i=0;
  while($all=$xoopsDB->fetchArray($result)){
    foreach($all as $k=>$v){
          $$k=$v;
    }
    $data[$i]['jb_sn']=$jb_sn;
    $data[$i]['jb_week']=$jb_week;
    $data[$i]['jbt_sn']=$jbt_sn;
    $data['jbt_sn'][]=$jbt_sn;
    $data['jbt_sn']=array_unique($data['jbt_sn']);
    ++$i;
  }
  return $data;
}
/*-----------執行動作判斷區----------*/
$op=empty($_REQUEST['op'])?"":$_REQUEST['op'];
$jb_sn=empty($_REQUEST['jb_sn'])?"":intval($_REQUEST['jb_sn']);
$jbt_sn=empty($_REQUEST['jbt_sn'])?"":intval($_REQUEST['jbt_sn']);
$jbi_sn=empty($_REQUEST['jbi_sn'])?"":intval($_REQUEST['jbi_sn']);



switch($op){
  /*---判斷動作請貼在下方---*/

  //新增資料
  case "insert_jill_booking":
  $sn=bath_insert();

  header("location: {$_SERVER['PHP_SELF']}?op=list_jill_booking&jb_sn={$sn['jb_sn']}&jbi_sn={$sn['jbi_sn']}");
  break;

  case "insert_jill_booking_date":
  $jbi_sn=insert_jill_booking_date($jb_sn);
  header("location:index.php?jbi_sn=$jbi_sn");
  break;


  case "list_jill_booking":
  list_jill_booking($jb_sn,$jbi_sn);
  break;


  case "jill_booking_form":
  jill_booking_form($jbt_sn,$jb_date);
  break;


  default:
    jill_booking_form($jbi_sn);
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