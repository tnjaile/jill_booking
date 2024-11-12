<?php

use XoopsModules\Jill_booking\Tools;
use XoopsModules\Tadtools\EasyResponsiveTabs;
use XoopsModules\Tadtools\Utility;

//區塊主函式 (jb_b_week_list)
function jb_b_week_list($options)
{
    global $xoopsDB, $xoTheme;
    $block['options0'] = $options[0];
    $block['weekArr'] = Tools::weekArr();
    //die(var_dump($block['weekArr']));

    $sql = 'SELECT `jbi_sn`,`jbi_title` FROM `' . $xoopsDB->prefix('jill_booking_item') . '` WHERE `jbi_enable`=1 AND ((NOW() BETWEEN `jbi_start` AND `jbi_end`) OR (TO_DAYS(NOW()) - TO_DAYS(`jbi_start`) >=0 AND `jbi_end`="0000-00-00")) ORDER BY `jbi_sort`';
    $result = Utility::query($sql);
    //die($sql);
    $block['content'] = array();
    $i = 0;
    while (list($jbi_sn, $jbi_title) = $xoopsDB->fetchRow($result)) {
        $block['content'][$i]['jbi_sn'] = $jbi_sn;
        $block['content'][$i]['jbi_title'] = $jbi_title;
        $block['content'][$i]['timeArr'] = Tools::get_bookingtime_jbisn($jbi_sn, 1);
        $block['content'][$i]['bookingArr'] = get_booking_table($block['weekArr'], $block['content'][$i]['timeArr']);
        $i++;
    }

    //避免js重複引入
    if ($xoTheme) {
        $xoTheme->addStylesheet('modules/tadtools/jquery.qtip/jquery.qtip.css');
        $xoTheme->addScript('modules/tadtools/jquery.qtip/jquery.qtip.js');
    }

    $randStr = Utility::randStr();
    $responsive_tabs = new EasyResponsiveTabs('#iteamtab' . $randStr, $options[0]);
    $responsive_tabs->rander();

    $block['randStr'] = $randStr;
    $block['height'] = $i * 60;
    //die(var_dump($block['content']));
    return $block;

}

//區塊編輯函式 (jb_b_week_list_edit)
function jb_b_week_list_edit($options)
{

    //"選單呈現類型"預設值
    $selected_0_0 = ($options[0] == 'accordion') ? 'selected' : '';
    $selected_0_1 = ($options[0] == 'default') ? 'selected' : '';
    $selected_0_2 = ($options[0] == 'vertical') ? 'selected' : '';

    $form = "
  <table>
    <tr>
      <th>
        <!--選單呈現類型-->
        " . _MB_JB_B_TODAY_LIST_OPT0 . "
      </th>
      <td>
        <select name='options[0]'>
          <option value='accordion' $selected_0_0>" . _MB_JB_B_TODAY_LIST_OPT0_VAL0 . "</option>
          <option value='default' $selected_0_1>" . _MB_JB_B_TODAY_LIST_OPT0_VAL1 . "</option>
          <option value='vertical' $selected_0_2>" . _MB_JB_B_TODAY_LIST_OPT0_VAL2 . "</option>
        </select>
      </td>
    </tr>
  </table>
  ";
    return $form;
}

function get_booking_table($weekArr = "", $timeArr = "")
{
    global $xoopsUser;
    $uid = !empty($xoopsUser) ? $xoopsUser->uid() : "";
    if (empty($timeArr)) {
        return;
    }

    //die(var_dump($timeArr));
    //場地預約起始日期
    $now = strtotime(date('Y-m-d'));
    //產生預約者資訊表格狀態值
    $bookingArr = array();
    //比對產生表單的陣列
    foreach ($timeArr as $t => $time) {
        $jbt_week = strval($time['jbt_week']);

        foreach ($weekArr as $wk => $weekinfo) {
            //預約日期
            $item_date = strtotime($weekinfo['d']);
            //預約者資訊
            $jbArr = Tools::get_booking_uid($time['jbt_sn'], $weekinfo['d']);

            //取得用了該日期時段的所有者
            $usershtml = Tools::booking_users($time['jbt_sn'], $weekinfo['d']);
            //將 uid 編號轉換成使用者姓名（或帳號）
            $uid_name = XoopsUser::getUnameFromId($jbArr['jb_uid'], 1);
            if (empty($uid_name)) {
                $uid_name = XoopsUser::getUnameFromId($jbArr['jb_uid'], 0);
            }
            $uid_name = (empty($jbArr['jb_status'])) ? _MD_APPROVING . ":{$uid_name}" : $uid_name;
            $color = "transparent";
            $content = "";
            if ($now > $item_date) {
                if (empty($jbArr['jb_sn'])) {
                    $content = _MD_NO_RECORD;
                    $color = "#959595";
                } else {
                    $content = "{$uid_name}";
                    $color = "#959595";
                }
            } else {
                //本週預約
                if (strpos($jbt_week, strval($wk)) !== false) {
                    if (empty($jbArr['jb_sn'])) {
                        $content = _MD_JILLBOOKIN_NO;
                        $color = "#EA4335";
                    } else {
                        $content = "{$uid_name}{$usershtml}";
                        $color = "#000000";
                    }
                }
            }

            $bookingArr[$t][$wk]['color'] = $color;
            $bookingArr[$t][$wk]['content'] = $content;
        }
    }

    return $bookingArr;
}
