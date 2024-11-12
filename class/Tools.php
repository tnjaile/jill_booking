<?php
namespace XoopsModules\Jill_booking;

use XoopsModules\Tadtools\Utility;

class Tools
{

    //取得用了該日期時段的所有者
    public static function booking_users($jbt_sn = "", $jb_date = "")
    {
        global $xoopsDB;

        $sql = 'SELECT a.`jb_waiting`, a.`jb_status`, b.`jb_booking_content`, c.`name`
            FROM `' . $xoopsDB->prefix('jill_booking_date') . '` AS a
            LEFT JOIN `' . $xoopsDB->prefix('jill_booking') . '` AS b ON a.`jb_sn`=b.`jb_sn`
            LEFT JOIN `' . $xoopsDB->prefix('users') . '` AS c ON b.`jb_uid`=c.`uid`
            WHERE a.`jbt_sn`=? AND a.`jb_date`=? ORDER BY a.`jb_waiting`';
        $result = Utility::query($sql, 'is', [$jbt_sn, $jb_date]);

        $totalnum = $xoopsDB->getRowsNum($result);
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
                $users .= "<li>{$name}{$jb_status}{$jb_booking_content}</li>";
            }
            $users .= "</ol>";
            $usershtml .= " <a href='#' qtipOpts=\"{}\" qtip-content='$users' ><i class='fa fa-list'></i></a>";
        }
        return $usershtml;
    }

    //取得用了該日期時段的uid
    public static function get_booking_uid($jbt_sn = "", $jb_date = "")
    {
        global $xoopsDB;
        //先抓核准通過的順位
        $sql = 'SELECT `jb_waiting` FROM `' . $xoopsDB->prefix('jill_booking_date') . '` WHERE `jbt_sn` =? AND `jb_date` =? AND `jb_status`=? ORDER BY `jb_waiting` ASC LIMIT 1';
        $result = Utility::query($sql, 'iss', [$jbt_sn, $jb_date, '1']);

        list($jb_waiting) = $xoopsDB->fetchRow($result);
        $where_jb_waiting = (empty($jb_waiting)) ? 'ORDER BY a.`jb_waiting` ASC LIMIT 1' : ' AND a.`jb_waiting`=? ';
        $sql2 = 'SELECT b.`jb_sn`, b.`jb_uid`, a.`jb_status`, b.`jb_booking_content` FROM `' . $xoopsDB->prefix('jill_booking_date') . '` AS a
                    LEFT JOIN `' . $xoopsDB->prefix('jill_booking') . '` AS b ON a.`jb_sn` = b.`jb_sn`
                    WHERE a.`jbt_sn` = ? AND a.`jb_date` = ? ' . $where_jb_waiting;
        $result2 = (empty($jb_waiting)) ? Utility::query($sql2, 'is', [$jbt_sn, $jb_date]) : Utility::query($sql2, 'iss', [$jbt_sn, $jb_date, $jb_waiting]);

        $data = $xoopsDB->fetchArray($result2);
        return $data;
    }

    //以jbi_sn取得jill_booking_time陣列
    public static function get_bookingtime_jbisn($jbi_sn = "")
    {
        global $xoopsDB;

        $sql = 'SELECT * FROM `' . $xoopsDB->prefix('jill_booking_time') . '` WHERE `jbi_sn`=? ORDER BY `jbt_sort`';
        $result = Utility::query($sql, 'i', [$jbi_sn]);
        $data = array();
        $i = 0;
        while ($all = $xoopsDB->fetchArray($result)) {
            foreach ($all as $k => $v) {
                $$k = $v;
            }
            //jbt_sn,jbi_sn,jbt_title,jbt_sort,jbt_week
            $data[$i]['jbt_sn'] = $jbt_sn;
            $data[$i]['jbi_sn'] = $jbi_sn;
            $data[$i]['jbt_title'] = $jbt_title;
            $data[$i]['jbt_sort'] = $jbt_sort;
            $data[$i]['jbt_week'] = $jbt_week;
            ++$i;
        }
        //die(var_export($data));
        return $data;
    }

    //週曆
    public static function weekArr($getdate = "")
    {
        if (!$getdate) {
            $getdate = date("Y-m-d");
        }

        //die($getdate);
        //取得一周的第幾天,星期天開始0-6
        $weekday = date("w", strtotime($getdate));
        //一週開始日期
        $week_start = date("Y-m-d", strtotime("$getdate -" . $weekday . " days"));
        $week = array();
        for ($i = 0; $i <= 6; $i++) {
            $week[$i]['d'] = date("Y-m-d", strtotime("$week_start +" . $i . " days"));
            $cweek = array(_MD_JILLBOOKIN_W0, _MD_JILLBOOKIN_W1, _MD_JILLBOOKIN_W2, _MD_JILLBOOKIN_W3, _MD_JILLBOOKIN_W4, _MD_JILLBOOKIN_W5, _MD_JILLBOOKIN_W6);
            if (in_array(date("w", strtotime($week[$i]['d'])), array_keys($cweek))) {
                $week[$i]['w'] = $cweek[$i];
            }
        }
        //die(var_export($week));
        return $week;
    }

    //立即寄出
    public static function send_now($email = "", $title = "", $content = "")
    {
        global $xoopsModuleConfig;
        $msg = '';
        if ($xoopsModuleConfig['can_send_mail']) {
            $xoopsMailer = &getMailer();
            $xoopsMailer->multimailer->ContentType = 'text/html';
            $xoopsMailer->addHeaders('MIME-Version: 1.0');

            $msg .= ($xoopsMailer->sendMail($email, $title, $content, $headers)) ? "已寄發通知信給 {$email}" : "通知信寄發給 {$email} 失敗！";
        } else {
            $msg = '目前設定為不寄發通知，請設法告知預約者預約結果';
        }
        return $msg;
    }

}
