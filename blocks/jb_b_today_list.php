<?php
use XoopsModules\Tadtools\EasyResponsiveTabs;
use XoopsModules\Tadtools\Utility;

//區塊主函式 (jb_b_today_list)
function jb_b_today_list($options)
{
    global $xoopsDB, $xoTheme;
    $block['options0'] = $options[0];
    $sql = 'SELECT `jbi_sn`,`jbi_title` FROM `' . $xoopsDB->prefix('jill_booking_item') . '` WHERE `jbi_enable`=1 AND ((NOW() BETWEEN `jbi_start` AND `jbi_end`) OR (TO_DAYS(NOW()) - TO_DAYS(`jbi_start`) >=0 AND `jbi_end`="0000-00-00")) ORDER BY `jbi_sort`';
    $result = $xoopsDB->query($sql);

    //die($sql);
    $block['content'] = array();
    $i = 0;
    while (list($jbi_sn, $jbi_title) = $xoopsDB->fetchRow($result)) {
        $block['content'][$i]['jbi_sn'] = $jbi_sn;
        $block['content'][$i]['jbi_title'] = $jbi_title;
        $block['content'][$i]['todaylist'] = get_todaylist($jbi_sn);
        $i++;
    }

    $randStr = XoopsModules\Tadtools\Utility::randStr();
    $responsive_tabs = new EasyResponsiveTabs('#iteamtab' . $randStr, $options[0]);
    $responsive_tabs->rander();

    $block['randStr'] = $randStr;
    $block['height'] = $i * 60;
    //die(var_dump($block['content']));
    return $block;

}

//區塊編輯函式 (jb_b_today_list_edit)
function jb_b_today_list_edit($options)
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

//取得用了該日期時段的uid
function get_todaylist($jbi_sn = "")
{
    global $xoopsDB;
    $jb_date = date("Y-m-d");
    $w_today = date("w");

    $sql2 = 'SELECT `jbt_sn`, `jbt_title`, `jbt_week` FROM `' . $xoopsDB->prefix('jill_booking_time') . '` WHERE `jbi_sn`=? ORDER BY `jbt_sort`';
    $result2 = Utility::query($sql2, 'i', [$jbi_sn]);
    $data = array();
    $j = 0;
    while (list($jbt_sn, $jbt_title, $jbt_week) = $xoopsDB->fetchRow($result2)) {
        $data[$j]['jbt_sn'] = $jbt_sn;
        $data[$j]['jbt_title'] = $jbt_title;
        // //抓預約者
        $sql = 'SELECT b.`jb_sn`, b.`jb_uid` FROM `' . $xoopsDB->prefix('jill_booking_date') . '` AS a
        LEFT JOIN `' . $xoopsDB->prefix('jill_booking') . '` AS b ON a.`jb_sn` = b.`jb_sn`
        WHERE a.`jb_date` = ? AND `jb_status` = ? AND a.`jbt_sn` = ? ORDER BY a.`jb_waiting` LIMIT 0, 1';
        $result = Utility::query($sql, 'ssi', [$jb_date, '1', $jbt_sn]);

        // die($sql);
        $i = 0;
        list($jb_sn, $jb_uid) = $xoopsDB->fetchRow($result);
        if (strpos($jbt_week, $w_today) !== false) {
            $data[$j]['name'] = (empty($jb_uid)) ? _MD_JILLBOOKIN_NO : XoopsUser::getUnameFromId($jb_uid, 1);
        } else {
            $data[$j]['name'] = _MD_EXPIRED;
        }

        $j++;
    }
    //die(var_dump($data));
    return $data;
}
