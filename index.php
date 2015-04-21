<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tnjaile 製作
// 製作日期：2015-01-23
// $Id:$
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
include "header.php";
$xoopsOption['template_main'] = set_bootstrap("jill_booking_index.html");
include_once XOOPS_ROOT_PATH."/header.php";

/*-----------功能函數區--------------*/
function booking_table($jbi_sn="",$getdate=""){
  global $xoopsDB , $xoopsTpl,$xoopsUser,$can_booking,$can_cancel;
  $uid=!empty($xoopsUser)?$xoopsUser->uid():"";
  //場地設定
  $item_opt=get_jill_booking_time_options($jbi_sn);
  if(!empty($jbi_sn)){
    //可啟用場地資訊
    $itemArr=get_jill_booking_item($jbi_sn,1);
    $jbi_sn=empty($itemArr['jbi_sn'])?"":$itemArr['jbi_sn'];

    if(!empty($itemArr)){
      //場地預約起始日期
      $start=strtotime($itemArr['jbi_start']);
      $now=strtotime(date('Y-m-d'));
      $start=($start<=$now)?$now:$start;
      //場地預約結束日期
      $end=(empty($itemArr['jbi_end']))?0:strtotime($itemArr['jbi_end']);

      //時段資訊
      $timeArr=get_bookingtime_jbisn($jbi_sn);
      $xoopsTpl->assign('timeArr' , $timeArr);

      //週曆
      $getdate =(empty($getdate))?date("Y-m-d"):$getdate;
      $weekArr=weekArr($getdate);

      //產生預約者資訊表格狀態值
      $bookingArr="";

      //比對產生表單的陣列
      foreach ($timeArr as $t => $time){
        $jbt_week=strval($time['jbt_week']);

        foreach ($weekArr as $wk => $weekinfo) {
          //預約日期
          $item_date=strtotime($weekinfo['d']);
          //預約者資訊
          $jbArr=get_booking_uid($time['jbt_sn'],$weekinfo['d']);

          //取得用了該日期時段的所有者
          $usershtml=booking_users($time['jbt_sn'],$weekinfo['d']);
          //將 uid 編號轉換成使用者姓名（或帳號）
          $uid_name=XoopsUser::getUnameFromId($jbArr['jb_uid'],1);
          if(empty($uid_name))$uid_name=XoopsUser::getUnameFromId($jbArr['jb_uid'],0);

          $color="transparent";
          $content=$status="";

          //過去預約
          if($start>$item_date){
            if(!empty($jbArr['jb_uid'])){
              $content=$uid_name;
              $color="#959595";
            }
          }
          elseif(($start<=$item_date and $end>=$item_date) or $end==0){
            //可預約期間
            if(strpos($jbt_week,strval($wk))!==false){
              if(empty($jbArr['jb_sn'])){
                if($can_booking){
                  $content="";
                  $status['func']="single_insert_booking";
                  $status['time']=$time['jbt_sn'];
                  $status['weekinfo']=$weekinfo['d'];
                  $status['jbi_sn']=$jbi_sn;
                }
                else{
                  $content=_MD_JILLBOOKIN_NO;
                  $color="#959595";
                }
              }
              else{
                if($uid==$jbArr['jb_uid'] or $can_cancel){
                  $content=delete_booking_icon($t,$wk,$time['jbt_sn'],$weekinfo['d'],$jbi_sn).$usershtml;
                  $color="#000000";
                }
                else{
                  $content="{$uid_name}{$usershtml}";
                  $color="#000000";
                }
              }
            }
          }
          $bookingArr[$t][$wk]['color']=$color;
          $bookingArr[$t][$wk]['status']=$status;
          $bookingArr[$t][$wk]['content']=$content;
        }
      }
      $xoopsTpl->assign('bookingArr' , $bookingArr);
      $xoopsTpl->assign('itemArr' , $itemArr);
      $xoopsTpl->assign('weekArr' , $weekArr);
      //die(var_export($bookingArr));
    }
  }

  $xoopsTpl->assign('jbi_sn' , $jbi_sn);
  $xoopsTpl->assign('item_opt' , $item_opt);
  $xoopsTpl->assign('now_op' , "booking_table");
  $xoopsTpl->assign('action' , $_SERVER['PHP_SELF']);
}

//週曆
function weekArr($getdate = ""){
  if(!$getdate) $getdate = date("Y-m-d");
  //die($getdate);
  //取得一周的第幾天,星期天開始0-6
  $weekday = date("w", strtotime($getdate));
  //一週開始日期
  $week_start= date("Y-m-d", strtotime("$getdate -".$weekday." days"));
  $week="";
  for ($i=0; $i <=6 ; $i++) {
    $week[$i]['d']=date("Y-m-d", strtotime("$week_start +".$i." days"));
    $cweek=array(_MD_JILLBOOKIN_W0,_MD_JILLBOOKIN_W1,_MD_JILLBOOKIN_W2,_MD_JILLBOOKIN_W3,_MD_JILLBOOKIN_W4,_MD_JILLBOOKIN_W5,_MD_JILLBOOKIN_W6);
    if(in_array(date("w",strtotime($week[$i]['d'])),array_keys($cweek))){
      $week[$i]['w']=$cweek[$i];
    }
  }
  //die(var_export($week));
  return $week;
}

//產生刪除預約鈕
function delete_booking_icon($t="",$wk="",$jbt_sn="",$jb_date="",$jbi_sn=""){
  global $xoopsUser;
  if(!isset($xoopsUser))return;
  $jbArr=get_booking_uid($jbt_sn,$jb_date);
  //將 uid 編號轉換成使用者姓名（或帳號）
  $uid_name=XoopsUser::getUnameFromId($jbArr['jb_uid'],1);
  if(empty($uid_name))$uid_name=XoopsUser::getUnameFromId($jbArr['jb_uid'],0);

  $icon="{$uid_name}<a href=\"javascript:delete_booking({$t} , {$wk} , {$jbt_sn},'{$jb_date}',{$jbi_sn});\" style='color:#D44950;' ><i class='fa fa-times' ></i></a>";
  return $icon;
}

//取得用了該日期時段的所有者
function booking_users($jbt_sn="",$jb_date=""){
  global $xoopsDB,$xoopsModule;

  $sql="select a.`jb_waiting`,c.`name`
        from ".$xoopsDB->prefix("jill_booking_date")." as a
        left join ".$xoopsDB->prefix("jill_booking")." as b on a.`jb_sn`=b.`jb_sn`
        left join ".$xoopsDB->prefix("users")." as c on b.`jb_uid`=c.`uid`
        where a.`jbt_sn`='{$jbt_sn}' and a.`jb_date`='{$jb_date}' order by a.`jb_waiting` ";
   //die($sql);
  $result=$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $totalnum=$xoopsDB->getRowsNum($result);
  $usershtml="";
  if($totalnum>1){
    $users="<ol>"._MD_JILLBOOKIN_JB_UID;
    while($all = $xoopsDB->fetchArray($result)){
      foreach($all as $k=>$v){
        $$k=$v;
      }
      //列出所有預約者資訊
      $users.="<li>$name</li>";
    }
    $users.="</ol>";
    $usershtml="<a href='#' class='users' data-toggle='popover' data-content='{$users}' ><i class='fa fa-exclamation'></i></a>";
  }
  return $usershtml;
}
//取得用了該日期時段的uid
function get_bookingArr($jbt_sn="",$jb_date=""){
  global $xoopsDB,$xoopsUser,$can_cancel,$isAdmin;
  if(!isset($xoopsUser))return;
  $uid=$xoopsUser->uid();
  $where=($can_cancel or $isAdmin)?"where a.`jbt_sn`='{$jbt_sn}' and a.`jb_date`='{$jb_date}' and a.`jb_status`='1' ":"where a.`jbt_sn`='{$jbt_sn}' and a.`jb_date`='{$jb_date}' and a.`jb_status`='1' and b.`jb_uid`='{$uid}'";

  $sql="select a.`jb_waiting`,b.* from ".$xoopsDB->prefix("jill_booking_date")." as a
  join ".$xoopsDB->prefix("jill_booking")." as b on a.`jb_sn`=b.`jb_sn` $where order by a.`jb_waiting`  ";
  //die($sql);
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $data=$xoopsDB->fetchArray($result);
  //die(var_export($data));
  return $data;
}
//取消預約
function delete_booking($jbt_sn="",$jb_date="",$jbi_sn=""){
  global $xoopsDB,$isAdmin;
  if(empty($jbt_sn)  or empty($jb_date) )return;
  $bookingArr=get_bookingArr($jbt_sn,$jb_date);
  //die(var_export($bookingArr));
  delete_jill_booking_date($bookingArr['jb_sn'],$jb_date,$jbt_sn);
  if(strtotime($bookingArr['jb_start_date'])==strtotime($bookingArr['jb_end_date'])){
    delete_jill_booking_week($bookingArr['jb_sn'],$jb_date,$jbt_sn);
    delete_jill_booking($bookingArr['jb_sn']);
  }
  header("location: {$_SERVER['PHP_SELF']}?op=booking_table&jbi_sn={$jbi_sn}&getdate={$jb_date}");
}
//刪除jill_booking_date某筆資料資料
function delete_jill_booking_date($jb_sn="",$jb_date="",$jbt_sn=""){
  global $xoopsDB, $isAdmin;

  if(empty($jb_sn) or empty($jb_date) or empty($jbt_sn))return;
  $sql = "select `jb_waiting` from `".$xoopsDB->prefix("jill_booking_date")."` where `jb_sn`='{$jb_sn}' and `jb_date`='{$jb_date}' and `jbt_sn` = '{$jbt_sn}' ";
    $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    list($seed_waiting)=$xoopsDB->fetchRow($result);

  $sql = "delete from `".$xoopsDB->prefix("jill_booking_date")."` where `jb_sn`='{$jb_sn}' and `jb_date`='{$jb_date}' and `jbt_sn` = '{$jbt_sn}'";
  //die($sql);
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  //更新jb_waiting
  $sql = "select * from `".$xoopsDB->prefix("jill_booking_date")."` where `jb_date`='{$jb_date}' and `jbt_sn` = '{$jbt_sn}' ";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  while($all=$xoopsDB->fetchArray($result)){
    foreach($all as $k=>$v){
          $$k=$v;
    }
    if($jb_waiting>$seed_waiting){
      $jb_waiting=$jb_waiting-1;
      $sql="update ".$xoopsDB->prefix("jill_booking_date")." set `jb_waiting`='{$jb_waiting}' where `jb_sn`='{$jb_sn}' and `jb_date`='{$jb_date}' and `jbt_sn` = '{$jbt_sn}' ";
      $xoopsDB->queryF($sql) or die(_TAD_SORT_FAIL." (".date("Y-m-d H:i:s").")".$sql);
    }

  }

}
//刪除jill_booking_week某筆資料資料
function delete_jill_booking_week($jb_sn="",$jb_date="",$jbt_sn=""){
  global $xoopsDB, $isAdmin;

  if(empty($jb_sn) or empty($jb_date) or empty($jbt_sn))return;

  $jb_week =date("w", strtotime($jb_date));

  $sql = "delete from `".$xoopsDB->prefix("jill_booking_week")."` where `jb_sn`='{$jb_sn}' and `jb_week`='{$jb_week}' and `jbt_sn` = '{$jbt_sn}'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

}
/*-----------執行動作判斷區----------*/
$op=empty($_REQUEST['op'])?"":$_REQUEST['op'];
$jbt_sn=empty($_REQUEST['jbt_sn'])?"":intval($_REQUEST['jbt_sn']);
$jbi_sn=empty($_REQUEST['jbi_sn'])?"":intval($_REQUEST['jbi_sn']);


switch($op){
  /*---判斷動作請貼在下方---*/

  case "single_insert_booking":
    $jb_sn=insert_jill_booking(1);
    $jb_week[$jbt_sn]=date("w",strtotime($_POST['jb_date']));
    insert_jill_booking_week($jb_sn,$jbt_sn,1,$jb_week);
    insert_jill_booking_date($jb_sn,1);
    $icon=delete_booking_icon($jbt_sn-1,$jb_week[$jbt_sn],$jbt_sn,$_POST['jb_date'],$jbi_sn);
    die($icon);
  break;

  case "delete_booking":
    if(is_date($_REQUEST['jb_date'])==1){
      //die($jbt_sn."==".$jbi_sn."==".$_REQUEST['jb_date']);
      delete_booking($jbt_sn,$_REQUEST['jb_date'],$jbi_sn);
    }

  break;

  case "jill_booking_form":
    if(is_date($_REQUEST['jb_date'])==1){
      jill_booking_form($jbt_sn,$_REQUEST['jb_date']);
    }
  break;

  case "booking_table":
    if(is_date($_REQUEST['getdate'])==1){
      booking_table($jbi_sn,$_REQUEST['getdate']);
    }
    else{
      booking_table($jbi_sn);
    }
  break;

  default:
    booking_table($jbi_sn);
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