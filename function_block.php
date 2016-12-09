<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tnjaile 製作
// 製作日期：2015-01-23
// $Id:$
// ------------------------------------------------------------------------- //
if (!function_exists("booking_users")) {
    //取得用了該日期時段的所有者
    function booking_users($jbt_sn = "", $jb_date = "")
    {
        global $xoopsDB, $xoopsModule;

        $sql = "select a.`jb_waiting`,a.`jb_status`,c.`name`
	        from " . $xoopsDB->prefix("jill_booking_date") . " as a
	        left join " . $xoopsDB->prefix("jill_booking") . " as b on a.`jb_sn`=b.`jb_sn`
	        left join " . $xoopsDB->prefix("users") . " as c on b.`jb_uid`=c.`uid`
	        where a.`jbt_sn`='{$jbt_sn}' and a.`jb_date`='{$jb_date}' order by a.`jb_waiting` ";
        //die($sql);
        $result    = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
        $totalnum  = $xoopsDB->getRowsNum($result);
        $usershtml = "";
        if ($totalnum > 1) {
            $users = _MD_JILLBOOKIN_JB_UID . "<ol>";
            while ($all = $xoopsDB->fetchArray($result)) {
                foreach ($all as $k => $v) {
                    $$k = $v;
                }
                //將是/否選項轉換為圖示
                $jb_status = ($jb_status == 1) ? '<img src="' . XOOPS_URL . '/modules/jill_booking/images/yes.gif" alt="' . _MD_PASS . '" title="' . _MD_PASS . '">' : '<img src="' . XOOPS_URL . '/modules/jill_booking/images/no.gif" alt="' . _MD_APPROVING . '" title="' . _MD_APPROVING . '">';
                //列出所有預約者資訊
                $users .= "<li>$name$jb_status</li>";
            }
            $users .= "</ol>";
            //$usershtml = "<a href='#' class='users' data-toggle='popover' data-content='{$users}' ><i class='fa fa-list'></i></a>";--bootstrap3 method
            $usershtml .= "<a href='#' qtipOpts=\"{}\" qtip-content='$users' ><i class='fa fa-list'></i></a>";
        }
        return $usershtml;
    }
}

if (!function_exists("get_booking_uid")) {
    //取得用了該日期時段的uid
    function get_booking_uid($jbt_sn = "", $jb_date = "")
    {
        global $xoopsDB, $xoopsModule;
        //先抓核准通過的順位
        $sql = "select jb_waiting from " . $xoopsDB->prefix("jill_booking_date") . "
	   where `jbt_sn`='{$jbt_sn}' && `jb_date`='{$jb_date}' && jb_status='1' ORDER BY jb_waiting ASC LIMIT 1 ";
        $result           = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
        list($jb_waiting) = $xoopsDB->fetchRow($result);
        $where_jb_waiting = (empty($jb_waiting)) ? "ORDER BY a.jb_waiting ASC LIMIT 1" : " && a.jb_waiting='{$jb_waiting}' ";
        $sql2             = "select b.jb_sn,b.jb_uid,a.jb_status from " . $xoopsDB->prefix("jill_booking_date") . " as a
	  left join " . $xoopsDB->prefix("jill_booking") . " as b on a.`jb_sn`=b.`jb_sn`
	   where a.`jbt_sn`='{$jbt_sn}' and a.`jb_date`='{$jb_date}' $where_jb_waiting  ";
        $result2 = $xoopsDB->query($sql2) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
        //die($sql2);
        list($jb_sn, $jb_uid, $jb_status) = $xoopsDB->fetchRow($result2);
        $data['jb_sn']                    = $jb_sn;
        $data['jb_uid']                   = $jb_uid;
        $data['jb_status']                = $jb_status;
        return $data;
    }
}

if (!function_exists("get_bookingtime_jbisn")) {
    //以jbi_sn取得jill_booking_time陣列
    function get_bookingtime_jbisn($jbi_sn = "")
    {
        global $xoopsDB;
        $sql    = "select * from `" . $xoopsDB->prefix("jill_booking_time") . "` where jbi_sn=$jbi_sn order by `jbt_sort`";
        $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'], 3, mysql_error());
        $data   = "";
        $i      = 0;
        while ($all = $xoopsDB->fetchArray($result)) {
            foreach ($all as $k => $v) {
                $$k = $v;
            }
            //jbt_sn,jbi_sn,jbt_title,jbt_sort,jbt_week
            $data[$i]['jbt_sn']    = $jbt_sn;
            $data[$i]['jbi_sn']    = $jbi_sn;
            $data[$i]['jbt_title'] = $jbt_title;
            $data[$i]['jbt_sort']  = $jbt_sort;
            $data[$i]['jbt_week']  = $jbt_week;
            ++$i;
        }
        //die(var_export($data));
        return $data;
    }

}

if (!function_exists("weekArr")) {
    //週曆
    function weekArr($getdate = "")
    {
        if (!$getdate) {
            $getdate = date("Y-m-d");
        }

        //die($getdate);
        //取得一周的第幾天,星期天開始0-6
        $weekday = date("w", strtotime($getdate));
        //一週開始日期
        $week_start = date("Y-m-d", strtotime("$getdate -" . $weekday . " days"));
        $week       = "";
        for ($i = 0; $i <= 6; $i++) {
            $week[$i]['d'] = date("Y-m-d", strtotime("$week_start +" . $i . " days"));
            $cweek         = array(_MD_JILLBOOKIN_W0, _MD_JILLBOOKIN_W1, _MD_JILLBOOKIN_W2, _MD_JILLBOOKIN_W3, _MD_JILLBOOKIN_W4, _MD_JILLBOOKIN_W5, _MD_JILLBOOKIN_W6);
            if (in_array(date("w", strtotime($week[$i]['d'])), array_keys($cweek))) {
                $week[$i]['w'] = $cweek[$i];
            }
        }
        //die(var_export($week));
        return $week;
    }
}
