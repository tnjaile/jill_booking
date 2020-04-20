<{$toolbar}>
<{$jquery}>
<script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>
<{if $now_op=="booking_table"}>
  <script type='text/javascript'>
    function single_insert_booking(t,wk,jbt_sn,jb_date,jbi_sn){
      $.post('<{$action}>', {op: 'single_insert_booking', jbt_sn: jbt_sn, jb_date: jb_date,jbi_sn:jbi_sn },
      function(data) {
        $('#submit'+t+'_'+wk).html(data).css('color','#000000');
      });
    }
    function delete_booking(t,wk,jbt_sn,jb_date,jbi_sn){
      var sure = window.confirm("<{$smarty.const._TAD_DEL_CONFIRM}>");
      if (!sure)  return;
      location.href="<{$action}>?op=delete_booking&jbt_sn=" + jbt_sn+"&jb_date="+jb_date+"&jbi_sn="+jbi_sn;
    }
    $(document).ready(function(){
      //$('.users').popover({placement:'top',html:'true',trigger:'hover'});
      $('a[qtipOpts]').qtip({
        metadata: {
            type: 'attr',
            name: 'qtipOpts'
        },
        content: {
            text: function(event, api) {
                // Retrieve content from custom attribute of the $('.selector') elements.
                return $(this).attr('qtip-content');
            }
        },
        style: {
            classes: 'qtip-blue qtip-rounded qtip-shadow'

        },
        position: {
          my:'Leftttop',
          at:'topRight'
        }
      });

      $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').focus()
      });
    });
  </script>

  <form action="<{$action}>" method="post" id="myForm" enctype="multipart/form-data">
    <select name="jbi_sn" id="jbi_sn" class="form-control" onChange="location.href='<{$action}>?jbi_sn='+this.value">
      <{$item_opt}>
    </select>
    <hr>
    <{if $jbi_sn}>
      <{assign var="start" value=$weekArr.0.d|date_format:"%Y-%m-%d"}>
      <table class="table table-striped table-hover table-bordered">
        <thead>
        <tr style="background-color: #F5F5F5;border: none;">
          <th colspan="8">
            <div class="col-sm-4 ">
              <a href="<{$action}>?op=booking_table&jbi_sn=<{$jbi_sn}>&getdate=<{"$start-1week"|date_format:"%Y-%m-%d"}> " class="btn btn-link"><i class="fa fa-long-arrow-left "></i><{$smarty.const._MD_LASTWEEK}></a>
            </div >
            <div class="col-sm-4 text-center" >
<!--                 <p style="vertical-align: middle;"><a href="#" title="<{$itemArr.jbi_desc}>" id="desc"><strong ><{$itemArr.jbi_title}></strong></a></p> -->
            <{$itemArr.jbi_title}>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-sm"><i class="fa fa-commenting-o" aria-hidden="true"></i></button>
            <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
              <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                  <{$itemArr.jbi_desc}>
                </div>
              </div>
            </div>
            </div>
            <div class="col-sm-4 text-right">
            <a href="<{$action}>?op=booking_table&jbi_sn=<{$jbi_sn}>&getdate=<{"$start+1week"|date_format:"%Y-%m-%d"}> " class="btn btn-link"><{$smarty.const._MD_NEXTWEEK}><i class="fa fa-long-arrow-right"></i></a>
            </div>
          </th>
        </tr>
        <tr style="background-color: #4682B4;color:white" rowspan="3">
          <th style="text-align:center;vertical-align: middle;"></th>
            <{foreach from=$weekArr item=week}>
              <th style="text-align:center;">
                <div ><{$week.d|date_format:'%Y'}></div>
                <div><{$week.d|date_format:'%m-%d'}></div>
                <div>(<{$week.w}>)</div>
              </th>
            <{/foreach}>
        </tr>
        </thead>
        <tbody>
          <{foreach from=$timeArr key=t item=time}>
            <tr >
              <td style="text-align:center;vertical-align: middle;"><{$time.jbt_title}></td>
              <{foreach from=$weekArr key=wk item=week}>

              <td>
                <div  id="submit<{$t}>_<{$wk}>" style='text-align: center; vertical-align: middle; color:<{$bookingArr.$t.$wk.color}>;'>
                  <{if $bookingArr.$t.$wk.status}>
                    <button type='button' class='btn btn-info'  onclick="<{$bookingArr.$t.$wk.status.func}>('<{$t}>' , '<{$wk}>' , '<{$bookingArr.$t.$wk.status.time}>','<{$bookingArr.$t.$wk.status.weekinfo}>','<{$bookingArr.$t.$wk.status.jbi_sn}>')"><i class='fa fa-pencil'></i></button>
                  <{else}>
                    <{$bookingArr.$t.$wk.content}>
                  <{/if}>
                </div>
              </td>
              <{/foreach}>
            </tr>
          <{/foreach}>
        </tbody>
      </table>
    <{else}>
      <div class="jumbotron">
        <div class="alert alert-info">
          <ul>
            <li>請在上方下拉選單內選擇場地</li>
            <li>若要預約請先登入</li>
          </ul>
        </div>
      </div>
    <{/if}>
  </form>
<{/if}>
