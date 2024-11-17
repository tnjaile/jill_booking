
  <{if $jill_book_adm|default:false}>
    <{$delete_jill_booking_item_func|default:''}>
  <{/if}>

  <h2 class="text-center"><{$jbi_title|default:''}></h2>

  <{if $jbi_desc|default:false}>
    <!--場地說明-->
    <div class="form-group row mb-3">
      <label class="col-sm-3 text-right text-end">
        <{$smarty.const._MA_JILLBOOKIN_JBI_DESC}>
      </label>
      <div class="col-sm-9">

        <div class="card card-body bg-light m-1">
          <{$jbi_desc|default:''}>
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
      <{$jbi_start|default:''}>
    </div>
  </div>

  <!--停用日期-->
  <div class="form-group row mb-3">
    <label class="col-sm-3 text-right text-end">
      <{$smarty.const._MA_JILLBOOKIN_JBI_END}>
    </label>
    <div class="col-sm-9">
      <{$jbi_end|default:''}>
    </div>
  </div>

  <!--是否可借-->
  <div class="form-group row mb-3">
    <label class="col-sm-3  text-right text-end">
      <{$smarty.const._MA_JILLBOOKIN_JBI_ENABLE}>
    </label>
    <div class="col-sm-9">
      <{$jbi_enable|default:''}>
    </div>
  </div>

  <!--審核者-->
  <div class="form-group row mb-3">
    <label class="col-sm-3  text-right text-end">
      <{$smarty.const._MA_JILLBOOKIN_JBI_APPROVAL}>
    </label>
    <div class="col-sm-9">
      <{$jbi_approval|default:''}>
    </div>
  </div>

  <div class="text-right text-end">
    <{if $jill_book_adm|default:false}>
      <a href="javascript:delete_jill_booking_item_func(<{$jbi_sn|default:''}>);" class="btn btn-sm btn-danger"><i class="fa fa-times" aria-hidden="true"></i> <{$smarty.const._TAD_DEL}></a>
      <a href="<{$xoops_url}>/modules/jill_booking/admin/main.php?op=jill_booking_item_form&jbi_sn=<{$jbi_sn|default:''}>" class="btn btn-sm btn-warning"><{$smarty.const._TAD_EDIT}></a>
      <a href="<{$xoops_url}>/modules/jill_booking/admin/main.php?op=jill_booking_item_form" class="btn btn-sm btn-primary"><{$smarty.const._MA_JILLBOOKIN_ADD}></a>
      <a href="<{$xoops_url}>/modules/jill_booking/admin/time.php?op=list_jill_booking_time&jbi_sn=<{$jbi_sn|default:''}>" class="btn btn-sm btn-success"><{$smarty.const._MA_JILLBOOKIN_SETTIME}></a>
      <a href="<{$xoops_url}>/modules/jill_booking/admin/approval.php?jbi_sn=<{$jbi_sn|default:''}>" class="btn btn-sm btn-primary"><{$smarty.const._MA_JILLBOOKIN_SETAPPROVAL}></a>
    <{/if}>
    <a href="<{$action|default:''}>" class="btn btn-sm btn-info"><{$smarty.const._MA_JILLBOOKIN_BACK}></a>
  </div>