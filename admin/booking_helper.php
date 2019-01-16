<?php
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = "booking_helper_adm_main.tpl";
include_once "header.php";
include_once "../function.php";
/*-----------執行動作判斷區----------*/
include_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
// $sn = system_CleanVars($_REQUEST, 'sn', 0, 'int');

switch ($op) {
    case 'get_times_of_item':
        // 全部時段 array
        // jbt_sn, jbi_sn, jbt_title, jbt_week, opened
        $data = getTimesByItem(trim($_GET['sn']), trim($_GET['date']));
        // 當日已預約時段  array
        // jbt_sn, jb_uid, jb_booking_content, name
        $used    = getUsedTimes($data, trim($_GET['date']));
        $used_sn = array_column($used, 'jbt_sn');

        // 轉換全部時段之資料陣列
        // 附加是否可預約標記，以及預約者姓名、預約事由
        // jbi_sn: 1
        // jbt_sn: 1
        // jbt_title: "第 1 節"
        // jbt_week: "2,3,4,5,6"
        // opened: true
        // can_order: false
        // event: "個人預約"
        // order_user: "管理員"
        $data = array_map(function ($orig) use ($used, $used_sn) {
            $key = array_search($orig['jbt_sn'], $used_sn);
            if ($key !== false) {
                // 已被預約，則設為不可預約且附加預約者姓名與事由
                $orig['can_order']  = false;
                $orig['order_user'] = $used[$key]['name'];
                $orig['event']      = $used[$key]['jb_booking_content'];
            } else {
                // 未被預約，則是否可預約設為是否開放之值
                $orig['can_order'] = $orig['opened'];
            }

            return $orig;
        }, $data);
        echo getJSONResponse($data);
        die();

    case 'create_orders':
        $data = file_get_contents("php://input");

        $result = createOrders(json_decode($data, true));

        die(getJSONResponse(compact('result')));

    default:
        show_content();
        break;
}

include_once 'footer.php';

/*-----------function區--------------*/

//顯示預設頁面內容
function show_content()
{
    // 加入日期選擇器
    // https://campus-xoops.tn.edu.tw/modules/tad_book3/page.php?tbdsn=874
    // http://my97.net/demo/index.htm
    include_once XOOPS_ROOT_PATH . "/modules/tadtools/cal.php";
    $cal = new My97DatePicker();
    $cal->render();

    global $xoopsTpl;

    $items = getAllItems();

    $xoopsTpl->assign('items', $items);
}

// 進行預約
function createOrders($data)
{
    global $xoopsDB, $xoopsUser;

    $jb_date = trim($data['jb_date']); // 起始日期

    /*
     * 因為是以批次預約方式，但原模組在刪除批次預約之預約時段紀錄時有 bug，
     * 若該筆批次預約單之 結束日期 與 起始日期 相同（本模組原來的模式），
     * 則刪除任一筆時段紀錄時會造成預約單刪除，但時段紀錄並未全數刪除，而造成問題。
     * https://campus-xoops.tn.edu.tw/modules/tad_discuss/discuss.php?DiscussID=5321&BoardID=66
     *
     * 暫時解法：
     *   將預約單之結束日期設為起始日期+1天
     */
    $jb_date_end = strtotime($jb_date); // 將日期轉為Unix時間戳記
    $jb_date_end = strtotime('+1 day', $jb_date_end); // 加一天
    $jb_date_end = date('Y-m-d', $jb_date_end); // 將Unix時間戳記轉回日期
    // die($jb_date_end);//會顯示如 2008-01-02

    // $jbi_sn = $data['jbi_sn']; // 場地 id
    $jbt_sn_array = $data['values']; // 時段 array
    // 進行預約之時間，取執行當下時間
    $orderAt = date_format(date_create(), 'Y-m-d H:i:s');
    $week    = date('w', strtotime($jb_date)); // 1-6, 0 星期日
    $uid     = $xoopsUser->uid();

    $event = trim($data['event']); // 理由
    $event = $event === '' ? '個人預約' : $event;

    // INSERT INTO `xx_jill_booking` (`jb_sn`, `jb_uid`, `jb_booking_time`, `jb_booking_content`, `jb_start_date`, `jb_end_date`) VALUES (1, 1, '2018-11-02 21:53:34', '個人預約', '2018-11-02', '2018-11-02');
    $sql = "INSERT INTO {$xoopsDB->prefix('jill_booking')} (`jb_uid`, `jb_booking_time`, `jb_booking_content`, `jb_start_date`, `jb_end_date`) VALUES ('{$uid}', '{$orderAt}', '{$event}', '{$jb_date}', '{$jb_date_end}')";
    $xoopsDB->query($sql) or web_error($sql);
    $jb_sn = $xoopsDB->getInsertId(); // 取得最新的預約單 id

    // INSERT INTO `xx_jill_booking_date` (`jb_sn`, `jb_date`, `jbt_sn`, `jb_waiting`, `jb_status`, `approver`, `pass_date`) VALUES (1, '2018-11-02', 12, 1, '1', 0, '0000-00-00');
    // $sql = "INSERT INTO {$xoopsDB->prefix('jill_booking_date')} (`jb_sn`, `jb_date`, `jbt_sn`, `jb_waiting`, `jb_status`, `approver`, `pass_date`) VALUES ('{$jb_sn}', '{$jb_date}', :jbt_sn, 1, '1', 0, '0000-00-00')";
    $sql = "INSERT INTO {$xoopsDB->prefix('jill_booking_date')} (`jb_sn`, `jb_date`, `jbt_sn`, `jb_waiting`, `jb_status`, `approver`, `pass_date`) VALUES ";

    $date_values = [];
    $week_values = [];
    foreach ($jbt_sn_array as $jbt_sn) {
        $date_values[] = "('{$jb_sn}', '{$jb_date}', '{$jbt_sn}', 1, '1', 0, '0000-00-00')";
        $week_values[] = "('{$jb_sn}', '{$week}', '{$jbt_sn}')";
    }

    $sql .= implode(',', $date_values);
    $xoopsDB->query($sql) or web_error($sql);

    // INSERT INTO `xx_jill_booking_week` (`jb_sn`, `jb_week`, `jbt_sn`) VALUES (1, 5,  12);
    $sql = "INSERT INTO {$xoopsDB->prefix('jill_booking_week')} (`jb_sn`, `jb_week`, `jbt_sn`) VALUES ";
    $sql .= implode(',', $week_values);
    $xoopsDB->query($sql) or web_error($sql);

    return true;
}

// 取得所有場地
function getAllItems()
{
    global $xoopsDB;

    $sql = "SELECT
                jbi_sn, jbi_title
            FROM
                {$xoopsDB->prefix('jill_booking_item')}";
    $result = $xoopsDB->query($sql);

    $data = [];
    while ($item = $xoopsDB->fetchArray($result)) {
        $data[] = $item;
    }
    ;

    return $data;
}

// 取得某場地可用之時段
function getTimesByItem($jbi_sn, $date)
{
    global $xoopsDB;

    $sql = "SELECT
                jbt_sn, jbi_sn, jbt_title, jbt_week
            FROM
                {$xoopsDB->prefix('jill_booking_time')}
            WHERE
                jbi_sn = {$jbi_sn}
            ORDER BY
                jbt_sort ASC";
    $result = $xoopsDB->query($sql);

    $data = [];
    $w    = date('w', strtotime($date)); // 0-6，日一二....六
    while ($item = $xoopsDB->fetchArray($result)) {
        // 附加該時段是否開放之資訊
        $item['opened'] = in_array($w, explode(',', $item['jbt_week']));
        $data[]         = $item;
    }
    ;

    return $data;
}

// 取得已預約之時段資料陣列
// jbt_sn, jb_uid, jb_booking_content, name
function getUsedTimes($times, $date)
{
    global $xoopsDB;

    // 取得全部時段之 jbt_sn
    $all_sn = array_column($times, 'jbt_sn');
    $all_sn = implode(',', $all_sn);

    $sql = "SELECT
            `jbt_sn`, `jb_uid`, `jb_booking_content`
        FROM
            {$xoopsDB->prefix('jill_booking_date')} AS t_date
        LEFT JOIN
            {$xoopsDB->prefix('jill_booking')} AS booking
        ON
            t_date.jb_sn = booking.jb_sn
        WHERE
            `jb_date` = '{$date}' AND `jbt_sn` IN ({$all_sn})";
    $result = $xoopsDB->query($sql);

    // 已預約之時段資料陣列，
    // jbt_sn, jb_uid, jb_booking_content, name
    $data = [];
    while ($item = $xoopsDB->fetchArray($result)) {
        // 附加預約者姓名
        $item['name'] = XoopsUser::getUnameFromId($item['jb_uid'], 1);
        $data[]       = $item;
    }
    ;

    return $data;
}

// 處理欲回傳之 json
function getJSONResponse($data)
{
    header('Content-Type:application/json;charset=utf-8');

    return json_encode($data, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
/*-----------秀出結果區--------------*/
$xoopsTpl->assign("isAdmin", true);
include_once 'footer.php';
