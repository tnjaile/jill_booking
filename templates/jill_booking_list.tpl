<{$toolbar|default:''}>
<h3 class='sr-only'><{$smarty.const._MD_JILLBOOKIN_SMNAME3}></h3>
<!--列出所有資料-->
<{if $now_op=="jill_booking_list"}>
  <div class="row mb-2">
    <div class="col-auto">
      <select id="jbi_sn" name="jbi_sn" class="form-control form-select " style="width: auto;" onchange="location.href='<{$action|default:''}>?jbi_sn='+this.value" title='jbi_sn'>
        <option value=""><{$smarty.const._MD_JILLBOOKIN_CHOOSEITEM}></option>
        <{$item_opt|default:''}>
      </select>
    </div>
  </div>

  <{if $all_content|default:false}>
    <{$delete_jill_booking_func|default:''}>

    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th class="col-sm-2">
            <!--場地-->
            <{$smarty.const._MD_JILLBOOKIN_ITEM}>
          </th>
          <th class="col-sm-2">
              <!--預約者-->
              <{$smarty.const._MD_JILLBOOKIN_JB_UID}>
          </th>
          <th class="col-sm-2">
            <!--預約日期-->
            <{$smarty.const._MD_JILLBOOKIN_JB_BOOKING_TIME}>
          </th>
          <th class="col-sm-1">
            <!--順位-->
            <{$smarty.const._MD_WAITING}>
          </th>
          <th class="col-sm-2">
            <!--理由-->
            <{$smarty.const._MD_JILLBOOKIN_CONTENT}>
          </th>
          <th class="col-sm-1">
            <!--是否核准-->
            <{$smarty.const._MD_JILLBOOKIN_JB_STATUS}>
          </th>
          <th class="col-sm-2"><{$smarty.const._TAD_FUNCTION}></th>

        </tr>
      </thead>

      <tbody id="jill_booking_sort">
        <{foreach from=$all_content item=data}>
          <tr id="tr_<{$data.jb_sn}>">
            <td>
              <!--場地-->
              <{$data.jbi_title}>
            </td>
            <td>
              <!--預約者-->
              <{$data.jb_uid}>
            </td>
            <td>
              <!--預約日期-->
              <{$data.jb_date}><{$data.jbt_title}>
            </td>
            <td>
              <!--順位-->
              <{$data.jb_waiting}>
            </td>
            <td>
              <!--理由-->
              <{$data.jb_booking_content}>
            </td>
            <td>
              <!--是否核准-->
              <{if $data.jb_status|default:false}>
                <img src="<{$xoops_url}>/modules/jill_booking/images/yes.gif" alt="<{$smarty.const._YES}>" title="<{$smarty.const._YES}>">
              <{else}>
                <img src="<{$xoops_url}>/modules/jill_booking/images/no.gif" alt="<{$smarty.const._NO}>" title="<{$smarty.const._NO}>">
              <{/if}>
            </td>
            <td>
              <{if $data.fun|default:false}>
                <a href="javascript:delete_jill_booking_func('<{$data.primary}>');" class="btn btn-sm btn-danger"><i class="fa fa-times" aria-hidden="true"></i> <{$smarty.const._TAD_DEL}></a>
              <{/if}>
            </td>
          </tr>
        <{/foreach}>
      </tbody>
    </table>

    <{$bar|default:''}>
  <{else}>
    <div class="row">
      <div class="alert alert-info text-center"><{$smarty.const._MD_NO_RECORD}></div>
    </div>
  <{/if}>
<{/if}>
