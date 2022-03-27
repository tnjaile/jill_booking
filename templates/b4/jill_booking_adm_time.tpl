
<{if $now_op=="list_jill_booking_time"}>
  <{$formValidator_code}>
  <{$jeditable_set}>
  <{$delete_jill_booking_time_func}>

  <script type='text/javascript'>
    $(document).ready(function(){
      $('#sort').sortable({ opacity: 0.6, cursor: 'move', update: function() {
        var order = $(this).sortable('serialize');
        $.post('save_sort.php', order, function(theResponse){
          $('#save_msg').html(theResponse);
        });
      }
      });
    });
    function change_enable(jbt_sn,w){
      $.post("time.php", {op: 'change_enable', jbt_sn: jbt_sn, week: w },
      function(data) {
        $('#'+jbt_sn+'_'+w).attr('src',data);
      });
    }
  </script>
  <h2><{$item.jbi_title}><{$smarty.const._MA_JILLBOOKIN_SETTIME}></h2>

  <{if $jbi_desc}>
    <!--場地說明-->
    <div class="row">
        <div class="card card-body bg-light m-1">
          <{$jbi_desc}>
        </div>
    </div>
  <{/if}>

  <div id="save_msg"></div>
  <div class="row">
    <div class="col-sm-7">
      <form action="time.php" method="post" id="myForm" enctype="multipart/form-data" role="form">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th rowspan="2" style="text-align: center; vertical-align: middle;">
                <!--時段標題-->
                <{$smarty.const._MA_JILLBOOKIN_JBT_TITLE}>
              </th>
              <th colspan="7" style="text-align: center;">
                <!--開放星期-->
                <{$smarty.const._MA_JILLBOOKIN_JBT_WEEK}>
              </th>
              <th rowspan="2" style="text-align: center; vertical-align: middle;">
                <{$smarty.const._TAD_FUNCTION}>
              </th>
            </tr>
            <tr>
              <th style="background-color: #4E525B;">
                <!--星期一-->
                <{$smarty.const._MA_JILLBOOKIN_W1}>
              </th>
              <th style="background-color: #4E525B;">
                <!--星期二-->
                <{$smarty.const._MA_JILLBOOKIN_W2}>
              </th>
              <th style="background-color: #4E525B;">
                <!--星期三-->
                <{$smarty.const._MA_JILLBOOKIN_W3}>
              </th>
              <th style="background-color: #4E525B;">
                <!--星期四-->
                <{$smarty.const._MA_JILLBOOKIN_W4}>
              </th>
              <th style="background-color: #4E525B;">
                <!--星期五-->
                <{$smarty.const._MA_JILLBOOKIN_W5}>
              </th>
              <th style="background-color: #4E525B;">
                <!--星期六-->
                <{$smarty.const._MA_JILLBOOKIN_W6}>
              </th>
              <th style="background-color: #4E525B;">
                <!--星期日-->
                <{$smarty.const._MA_JILLBOOKIN_W0}>
              </th>
            </tr>
          </thead>

          <tbody id="sort">
            <{if $all_content}>
              <{foreach from=$all_content item=data}>
                <tr id='tr_<{$data.jbt_sn}>'>
                  <td style="text-align: center;">
                    <!--時段標題-->
                    <div id="jbt_title_<{$data.jbt_sn}>"><{$data.jbt_title}></div>
                  </td>
                  <td style="text-align: center;">
                    <{$data.w1}>
                  </td>
                  <td style="text-align: center;">
                    <{$data.w2}>
                  </td>
                  <td style="text-align: center;">
                    <{$data.w3}>
                  </td>
                  <td style="text-align: center;">
                    <{$data.w4}>
                  </td>
                  <td style="text-align: center;">
                    <{$data.w5}>
                  </td>
                  <td style="text-align: center;">
                    <{$data.w6}>
                  </td>
                  <td style="text-align: center;">
                    <{$data.w0}>
                  </td>
                  <td style="text-align: center;">
                    <img src='<{$xoops_url}>/modules/tadtools/treeTable/images/updown_s.png' style='cursor: s-resize;margin:0px 4px;' alt='<{$smarty.const._TAD_SORTABLE}>' title='<{$smarty.const._TAD_SORTABLE}>'>
                    <{if $data.booking_times!=""}>
                      <{$data.booking_times}>
                    <{else}>
                      <a href="javascript:delete_jill_booking_time_func(<{$data.jbt_sn}>);" class="btn btn-sm btn-danger"><{$smarty.const._TAD_DEL}></a>
                    <{/if}>
                  </td>
                </tr>
              <{/foreach}>
            <{/if}>
          </tbody>
          <tr>
            <td>
              <input type="text" name="jbt_title" id="jbt_title" class="form-control validate[required] " value="<{$jbt_title}>" placeholder="<{$smarty.const._MA_JILLBOOKIN_JBT_TITLE}>">
            </td>
            <td style="text-align: center;">
              <input type="checkbox" name="jbt_week[]" value="1" checked="checked">
            </td>
            <td style="text-align: center;">
              <input type="checkbox" name="jbt_week[]" value="2" checked="checked">
            </td>
            <td style="text-align: center;">
              <input type="checkbox" name="jbt_week[]" value="3" checked="checked">
            </td>
            <td style="text-align: center;">
              <input type="checkbox" name="jbt_week[]" value="4" checked="checked">
            </td>
            <td style="text-align: center;">
              <input type="checkbox" name="jbt_week[]" value="5" checked="checked">
            </td>
            <td style="text-align: center;">
              <input type="checkbox" name="jbt_week[]" value="6">
            </td>
            <td style="text-align: center;">
              <input type="checkbox" name="jbt_week[]" value="0">
            </td>
            <td>
              <input type="hidden" name="jbi_sn" value="<{$jbi_sn}>">
              <input type="hidden" name="op" value="insert_jill_booking_time">
              <button type="submit" class="btn btn-secondary btn-outline-info"><{$smarty.const._MA_JILLBOOKIN_ADD_TIME}></button>
            </td>
          </tr>
        </table>
      </form>
    </div>
    <div class="col-sm-5">
      <{if !$all_content}>
        <div class="list-group">
          <a href="#" class="list-group-item active">
            <{$smarty.const._MA_JILLBOOKIN_IMPORT}>
          </a>
          <a href="time.php?op=import_time&jbi_sn=<{$jbi_sn}>&type=18" class="list-group-item"><{$smarty.const._MA_JILLBOOKIN_IMPORT_18}></a>
          <a href="time.php?op=import_time&jbi_sn=<{$jbi_sn}>&type=apm" class="list-group-item"><{$smarty.const._MA_JILLBOOKIN_IMPORT_APM}></a>
          <{foreach from=$place_time item=place}>
            <a href="time.php?op=copy_time&jbi_sn=<{$place.jbi_sn}>&to_jbi_sn=<{$jbi_sn}>" class="list-group-item"><{$place.jbi_link}></a>
          <{/foreach}>
        </div>
      <{/if}>
    </div>
  </div>

<{/if}>
