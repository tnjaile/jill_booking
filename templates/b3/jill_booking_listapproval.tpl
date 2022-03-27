<{$toolbar}>
<h3 class='sr-only'><{$smarty.const._MD_JILLBOOKIN_SMNAME4}></h3>
<!--顯示表單-->
<{if $now_op=="jill_booking_approvallist"}>
  <script type='text/javascript'>
    function update_jb_status_func(jb_sn,jb_date,jbt_sn,jbi_sn,i){
      $.post("listapproval.php", {op: 'update_jb_status', jb_sn: jb_sn, jb_date: jb_date,jbt_sn: jbt_sn, jbi_sn: jbi_sn },
      function(data) {
        if(data=='1'){
          $('#pass').addClass('col-sm-2 alert alert-success') ;
          $('#pass').text('<{$smarty.const._MD_UPDATE_COMPLETED}>');
          $('.'+jb_date+'_'+jbt_sn).remove();
        }
        else{
          $('#pass').addClass('span2 alert alert-danger') ;
          $('#pass').text('<{$smarty.const._MD_UPDATE_FAILED}>');
        }
      });
    }
  </script>
  <div class="controls controls-row">
    <div class="col-sm-2">
      <select name="jbi_sn" id="jbi_sn" class="form-control" size='10'  onChange="location.href='<{$action}>?jbi_sn='+this.value"  title='jbi_sn'>
       <{$item_opt}>
      </select>
    </div>
    <div class="col-sm-10">
      <{if $all_content}>
        <{$delete_jill_booking_func}>
        <div id="pass"></div>
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>
                  <!--預約者-->
                  <{$smarty.const._MD_JILLBOOKIN_JB_UID}>
              </th>
              <th>
                  <!--預約日期-->
                  <{$smarty.const._MD_JILLBOOKIN_JB_BOOKING_TIME}>
              </th>
              <th>
                  <!--星期--><!--節次-->
                  <{$smarty.const._MD_JWEEK}> / <{$smarty.const._MD_JSESSION}>
              </th>
              <th>
                  <!--順位-->
                  <{$smarty.const._MD_WAITING}>
              </th>
              <!--<th>
                已審核者
                <{$smarty.const._MD_JILLBOOKIN_HADPASS}>
              </th>-->
              <th>
                <!-- 理由 -->
                <{$smarty.const._MD_JILLBOOKIN_CONTENT}>
              </th>
              <th><{$smarty.const._TAD_FUNCTION}></th>

            </tr>
          </thead>

          <tbody id="jill_booking_sort">
            <{foreach from=$all_content key=i item=data}>
              <tr id="tr_<{$i}>" class="<{$data.jb_date}>_<{$data.jbt_sn}>">
                <td>
                  <!--預約者-->
                  <{$data.jb_uid}>
                </td>
                <td>
                  <!--預約日期-->
                  <div><{$data.jb_date}></div>
                  <div style="font-size:0.6em;color: #EA4335;"><{$data.had_pass}><div>
                </td>
                <td>
                  <!--星期--><!--節次-->
                  <{if $data.jb_week=="0"}><{$smarty.const._MD_JILLBOOKIN_W0}> / <{$data.jbt_title}>
                  <{elseif $data.jb_week=="1"}><{$smarty.const._MD_JILLBOOKIN_W1}> / <{$data.jbt_title}>
                  <{elseif $data.jb_week=="2"}><{$smarty.const._MD_JILLBOOKIN_W2}> / <{$data.jbt_title}>
                  <{elseif $data.jb_week=="3"}><{$smarty.const._MD_JILLBOOKIN_W3}> / <{$data.jbt_title}>
                  <{elseif $data.jb_week=="4"}><{$smarty.const._MD_JILLBOOKIN_W4}> / <{$data.jbt_title}>
                  <{elseif $data.jb_week=="5"}><{$smarty.const._MD_JILLBOOKIN_W5}> / <{$data.jbt_title}>
                  <{else }><{$smarty.const._MD_JILLBOOKIN_W6}> / <{$data.jbt_title}>
                  <{/if}>

                </td>

                <td>
                  <!--順位-->
                  <{$data.jb_waiting}>

                </td>

                <!--<td>
                  通過審核者
                  <{$data.had_pass}>
                </td>-->
                <td>
                  <!-- 理由 -->
                  <{$data.jb_booking_content}>
                </td>
                <td>
                  <a href="javascript:delete_jill_booking_func('<{$data.jb_sn}>_<{$data.jbt_sn}>_<{$data.jb_date}>_<{$data.jbi_sn}>');" class="btn btn-mini btn-danger"><{$smarty.const._TAD_DEL}></a>
                  <a href="javascript:update_jb_status_func(<{$data.jb_sn}>,'<{$data.jb_date}>',<{$data.jbt_sn}>,<{$data.jbi_sn}>,<{$i}>);" class="btn btn-primary"><{$smarty.const._MD_PASS}></a>
                </td>
              </tr>
            <{/foreach}>
          </tbody>
        </table>

        <{$bar}>
      <{else}>
        <div class="well">
          <div class="alert alert-info text-center"><{$smarty.const._MD_NO_RECORD}></div>
        </div>
      <{/if}>
    </div>
  </div>
<{/if}>
