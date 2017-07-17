
<!--顯示表單-->
<script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>

<{if $now_op=="jill_booking_item_form"}>
  <div class="container">
    <!--套用formValidator驗證機制-->
    <{$formValidator_code}>
    <form action="<{$action}>" method="post" id="myForm" enctype="multipart/form-data" class="form-horizontal" role="form">
      <!--場地名稱-->
      <div class="row">
        <label class="col-sm-2">
          <{$smarty.const._MA_JILLBOOKIN_JBI_TITLE}>
        </label>
        <div class="col-sm-10">
          <input type="text" name="jbi_title" id="jbi_title" class="col-sm-12 " value="<{$jbi_title}>" placeholder="<{$smarty.const._MA_JILLBOOKIN_JBI_TITLE}>">
        </div>
      </div>

      <!--場地說明-->
      <div class="row">
        <label class="col-sm-2">
          <{$smarty.const._MA_JILLBOOKIN_JBI_DESC}>
        </label>
        <div class="col-sm-10">
          <{$Editor_code}>
        </div>
      </div>

      <!--啟用日期-->
      <div class="row">
        <label class="col-sm-2">
          <{$smarty.const._MA_JILLBOOKIN_JBI_START}>
        </label>
        <div class="col-sm-6">
          <input type="text" name="jbi_start" id="jbi_start" class="col-sm-12 " value="<{$jbi_start}>"  onClick="WdatePicker({dateFmt:'yyyy-MM-dd' , startDate:'%y-%M-%d'})" placeholder="<{$smarty.const._MA_JILLBOOKIN_JBI_START}>">
        </div>
      </div>

      <!--停用日期-->
      <div class="row">
        <label class="col-sm-2">
          <{$smarty.const._MA_JILLBOOKIN_JBI_END}>
        </label>
        <div class="col-sm-6">
          <input type="text" name="jbi_end" id="jbi_end" class="col-sm-12 " value="<{$jbi_end}>"  onClick="WdatePicker({dateFmt:'yyyy-MM-dd' , startDate:'%y-%M-%d'})" placeholder="<{$smarty.const._MA_JILLBOOKIN_JBI_END}>">
        </div>
      </div>

      <!--場地排序-->
      <!-- <div class="row">
        <label class="col-sm-2">
          <{$smarty.const._MA_JILLBOOKIN_JBI_SORT}>
        </label>
        <div class="col-sm-2">
          <input type="text" name="jbi_sort" id="jbi_sort" class="col-sm-12" value="<{$jbi_sort}>" placeholder="<{$smarty.const._MA_JILLBOOKIN_JBI_SORT}>">
        </div>
      </div> -->

      <!--是否可借-->
      <div class="row">
        <label class="col-sm-2">
          <{$smarty.const._MA_JILLBOOKIN_JBI_ENABLE}>
        </label>
        <div class="col-sm-10">

        <label class="radio-inline">
          <input type="radio" name="jbi_enable" id="jbi_enable_1" value="1" <{if $jbi_enable == "1"}>checked="checked"<{/if}>><{$smarty.const._YES}>
        </label>
        <label class="radio-inline">
          <input type="radio" name="jbi_enable" id="jbi_enable_0" value="0" <{if $jbi_enable == "0"}>checked="checked"<{/if}>><{$smarty.const._NO}>
        </label>
        </div>
      </div>


      <div class="text-center">

      <!--場地編號-->
      <input type='hidden' name="jbi_sn" value="<{$jbi_sn}>">

        <{$token_form}>

        <input type="hidden" name="op" value="<{$next_op}>">
        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
      </div>
    </form>
  </div>
<{/if}>


<!--顯示某一筆資料-->
<{if $now_op=="show_one_jill_booking_item"}>
  <{if $isAdmin}>
    <{$delete_jill_booking_item_func}>
  <{/if}>

  <h2 class="text-center"><{$jbi_title}></h2>

  <{if $jbi_desc}>
    <!--場地說明-->
    <div class="row">
      <label class="col-sm-3 text-right">
        <{$smarty.const._MA_JILLBOOKIN_JBI_DESC}>
      </label>
      <div class="col-sm-9">

        <div class="well">
          <{$jbi_desc}>
        </div>
      </div>
    </div>
  <{/if}>


  <!--啟用日期-->
  <div class="row">
    <label class="col-sm-3 text-right">
      <{$smarty.const._MA_JILLBOOKIN_JBI_START}>
    </label>
    <div class="col-sm-9">
      <{$jbi_start}>
    </div>
  </div>

  <!--停用日期-->
  <div class="row">
    <label class="col-sm-3 text-right">
      <{$smarty.const._MA_JILLBOOKIN_JBI_END}>
    </label>
    <div class="col-sm-9">
      <{$jbi_end}>
    </div>
  </div>

  <!--是否可借-->
  <div class="row">
    <label class="col-sm-3  text-right">
      <{$smarty.const._MA_JILLBOOKIN_JBI_ENABLE}>
    </label>
    <div class="col-sm-9">
      <{$jbi_enable}>
    </div>
  </div>

  <!--審核者-->
  <div class="row">
    <label class="col-sm-3  text-right">
      <{$smarty.const._MA_JILLBOOKIN_JBI_APPROVAL}>
    </label>
    <div class="col-sm-9">
      <{$jbi_approval}>
    </div>
  </div>

  <div class="text-right">
    <{if $isAdmin}>
      <a href="javascript:delete_jill_booking_item_func(<{$jbi_sn}>);" class="btn btn-danger"><{$smarty.const._TAD_DEL}></a>
      <a href="<{$xoops_url}>/modules/jill_booking/admin/main.php?op=jill_booking_item_form&jbi_sn=<{$jbi_sn}>" class="btn btn-warning"><{$smarty.const._TAD_EDIT}></a>
      <a href="<{$xoops_url}>/modules/jill_booking/admin/main.php?op=jill_booking_item_form" class="btn btn-primary"><{$smarty.const._MA_JILLBOOKIN_ADD}></a>
      <a href="<{$xoops_url}>/modules/jill_booking/admin/time.php?op=list_jill_booking_time&jbi_sn=<{$jbi_sn}>" class="btn btn-success"><{$smarty.const._MA_JILLBOOKIN_SETTIME}></a>
      <a href="<{$xoops_url}>/modules/jill_booking/admin/approval.php?op=list_jill_booking_approval&jbi_sn=<{$jbi_sn}>" class="btn btn-primary"><{$smarty.const._MA_JILLBOOKIN_SETAPPROVAL}></a>
    <{/if}>
    <a href="<{$action}>" class="btn btn-info"><{$smarty.const._MA_JILLBOOKIN_BACK}></a>
  </div>
<{/if}>

<!--列出所有資料-->
<{if $now_op=="list_jill_booking_item"}>
  <{if $all_content}>
    <{if $isAdmin}>
      <{$delete_jill_booking_item_func}>
      <{$jill_booking_item_jquery_ui}>
      <script type="text/javascript">
      $(document).ready(function(){
        $("#jill_booking_item_sort").sortable({ opacity: 0.6, cursor: "move", update: function() {
          var order = $(this).sortable("serialize");
          $.post("<{$xoops_url}>/modules/jill_booking/admin/main.php", order + "&op=update_jill_booking_item_sort", function(theResponse){
            $("#jill_booking_item_save_msg").html(theResponse);
          });
        }
        });
      });
      </script>
    <{/if}>
    <div id="jill_booking_item_save_msg"></div>
    <table class="table table-striped table-hover">
      <thead>
        <tr>

          <th>
            <!--場地名稱-->
            <{$smarty.const._MA_JILLBOOKIN_JBI_TITLE}>
          </th>
          <th>
            <!--啟用日期-->
            <{$smarty.const._MA_JILLBOOKIN_JBI_PERIOD}>
          </th>
          <th>
            <!--是否可借-->
            <{$smarty.const._MA_JILLBOOKIN_JBI_ENABLE}>
          </th>
          <th>
            <!--審核者-->
            <{$smarty.const._MA_JILLBOOKIN_JBI_APPROVAL}>
          </th>
          <{if $isAdmin}>
            <th><{$smarty.const._TAD_FUNCTION}></th>
          <{/if}>
        </tr>
      </thead>

      <tbody id="jill_booking_item_sort">
        <{foreach from=$all_content item=data}>
          <tr  id="tr_<{$data.jbi_sn}>">

            <td>
              <!--場地名稱-->
              <a href="time.php?jbi_sn=<{$data.jbi_sn}>"><{$data.jbi_title}></a>
            </td>
            <td>
              <!--啟用日期-->
              <{$data.jbi_start}>~<{$data.jbi_end}>
            </td>
            <td>
              <!--是否可借-->
              <{$data.jbi_enable}>
            </td>
            <td>
              <!--審核者-->
              <{$data.jbi_approval}>
            </td>

            <{if $isAdmin}>
              <td>
                <a href="javascript:delete_jill_booking_item_func(<{$data.jbi_sn}>);" class="btn btn-xs btn-danger"><{$smarty.const._TAD_DEL}></a>
                <a href="<{$xoops_url}>/modules/jill_booking/admin/main.php?op=jill_booking_item_form&jbi_sn=<{$data.jbi_sn}>" class="btn btn-xs btn-warning"><{$smarty.const._TAD_EDIT}></a>
                <a href="<{$xoops_url}>/modules/jill_booking/admin/time.php?op=list_jill_booking_time&jbi_sn=<{$data.jbi_sn}>" class="btn btn-xs btn-success"><{$smarty.const._MA_JILLBOOKIN_SETTIME}></a>
                <a href="<{$xoops_url}>/modules/jill_booking/admin/approval.php?op=list_jill_booking_approval&jbi_sn=<{$data.jbi_sn}>" class="btn btn-xs btn-primary"><{$smarty.const._MA_JILLBOOKIN_SETAPPROVAL}></a>
                <img src="<{$xoops_url}>/modules/tadtools/treeTable/images/updown_s.png" style="cursor: s-resize;margin:0px 4px;" alt="<{$smarty.const._TAD_SORTABLE}>" title="<{$smarty.const._TAD_SORTABLE}>">
              </td>
            <{/if}>
          </tr>
        <{/foreach}>
      </tbody>
    </table>


    <{if $isAdmin}>
      <div class="text-right">
        <a href="<{$xoops_url}>/modules/jill_booking/admin/main.php?op=jill_booking_item_form" class="btn btn-info"><{$smarty.const._MA_JILLBOOKIN_ADD}></a>
      </div>
    <{/if}>

    <{$bar}>
  <{else}>
    <{if $isAdmin}>
      <div class="jumbotron text-center">
        <a href="<{$xoops_url}>/modules/jill_booking/admin/main.php?op=jill_booking_item_form" class="btn btn-info"><{$smarty.const._MA_JILLBOOKIN_ADD}></a>
      </div>
    <{/if}>
  <{/if}>
<{/if}>
