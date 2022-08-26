
  <{if $isAdmin}>
    <{$delete_jill_booking_item_func}>
  <{/if}>

  <h2 class="text-center"><{$jbi_title}></h2>

  <{if $jbi_desc}>
    <!--場地說明-->
    <div class="form-group row mb-3">
      <label class="col-sm-3 text-right text-end">
        <{$smarty.const._MA_JILLBOOKIN_JBI_DESC}>
      </label>
      <div class="col-sm-9">

        <div class="card card-body bg-light m-1">
          <{$jbi_desc}>
        </div>
      </div>
    </div>
  <{/if}>


  <!--啟用日期-->
  <div class="form-group row mb-3">
    <label class="col-sm-3 text-right text-end">
      <{$smarty.const._MA_JILLBOOKIN_JBI_START}>
    </label>
    <div class="col-sm-9">
      <{$jbi_start}>
    </div>
  </div>

  <!--停用日期-->
  <div class="form-group row mb-3">
    <label class="col-sm-3 text-right text-end">
      <{$smarty.const._MA_JILLBOOKIN_JBI_END}>
    </label>
    <div class="col-sm-9">
      <{$jbi_end}>
    </div>
  </div>

  <!--是否可借-->
  <div class="form-group row mb-3">
    <label class="col-sm-3  text-right text-end">
      <{$smarty.const._MA_JILLBOOKIN_JBI_ENABLE}>
    </label>
    <div class="col-sm-9">
      <{$jbi_enable}>
    </div>
  </div>

  <!--審核者-->
  <div class="form-group row mb-3">
    <label class="col-sm-3  text-right text-end">
      <{$smarty.const._MA_JILLBOOKIN_JBI_APPROVAL}>
    </label>
    <div class="col-sm-9">
      <{$jbi_approval}>
    </div>
  </div>

  <div class="text-right text-end">
    <{if $isAdmin}>
      <a href="javascript:delete_jill_booking_item_func(<{$jbi_sn}>);" class="btn btn-danger"><{$smarty.const._TAD_DEL}></a>
      <a href="<{$xoops_url}>/modules/jill_booking/admin/main.php?op=jill_booking_item_form&jbi_sn=<{$jbi_sn}>" class="btn btn-warning"><{$smarty.const._TAD_EDIT}></a>
      <a href="<{$xoops_url}>/modules/jill_booking/admin/main.php?op=jill_booking_item_form" class="btn btn-primary"><{$smarty.const._MA_JILLBOOKIN_ADD}></a>
      <a href="<{$xoops_url}>/modules/jill_booking/admin/time.php?op=list_jill_booking_time&jbi_sn=<{$jbi_sn}>" class="btn btn-success"><{$smarty.const._MA_JILLBOOKIN_SETTIME}></a>
      <a href="<{$xoops_url}>/modules/jill_booking/admin/approval.php?jbi_sn=<{$jbi_sn}>" class="btn btn-primary"><{$smarty.const._MA_JILLBOOKIN_SETAPPROVAL}></a>
    <{/if}>
    <a href="<{$action}>" class="btn btn-info"><{$smarty.const._MA_JILLBOOKIN_BACK}></a>
  </div>