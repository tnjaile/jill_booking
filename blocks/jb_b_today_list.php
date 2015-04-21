<?php

//  ------------------------------------------------------------------------ //
// 本模組由 admin 製作
// 製作日期：2015-01-23
// $Id:$
// ------------------------------------------------------------------------- //

//區塊主函式 (jb_b_today_list)
function jb_b_today_list($options){
 global $xoopsDB;

  //{$options[0]} : 秀出
  $block['options0']=$options[0];
  $sql="select * from `".$xoopsDB->prefix("jill_booking")."` order by `jb_start_date` desc";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $content='';
  $i=0;
  while($all = $xoopsDB->fetchArray($result)){
    $content[$i]=$all;
    $i++;
  }
  $block['bootstrap_version']=$_SESSION['bootstrap'];
  $block['content']=$content;
  return $block;
}


//區塊編輯函式 (jb_b_today_list_edit)
function jb_b_today_list_edit($options){


  $form="
  <table>
    <tr>
      <th>
        <!--秀出-->
        "._MB_JB_B_TODAY_LIST_OPT0."
      </th>
      <td>
        <input type='text' name='options[0]' value='{$options[0]}'>
      </td>
    </tr>
  </table>
  ";
  return $form;
}

?>