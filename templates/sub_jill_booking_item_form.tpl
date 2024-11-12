<div class="container">
  <!--套用formValidator驗證機制-->
  <{$formValidator_code|default:''}>
  <form action="<{$action|default:''}>" method="post" id="myForm" role="form">
    <!--場地名稱-->
    <div class="form-group row mb-3">
      <label class="col-sm-2 col-form-label text-sm-right  text-sm-end">
        <{$smarty.const._MA_JILLBOOKIN_JBI_TITLE}>
      </label>
      <div class="col-sm-10">
        <input type="text" name="jbi_title" id="jbi_title" class="form-control " value="<{$jbi_title|default:''}>" placeholder="<{$smarty.const._MA_JILLBOOKIN_JBI_TITLE}>">
      </div>
    </div>

    <!--啟用日期-->
    <div class="form-group row mb-3">
      <label class="col-sm-2 col-form-label text-sm-right  text-sm-end">
        <{$smarty.const._MA_JILLBOOKIN_JBI_START}>
      </label>
      <div class="col-sm-6">
        <input type="text" name="jbi_start" id="jbi_start" class="form-control " value="<{$jbi_start|default:''}>"  onClick="WdatePicker({dateFmt:'yyyy-MM-dd' , startDate:'%y-%M-%d'})" placeholder="<{$smarty.const._MA_JILLBOOKIN_JBI_START}>">
      </div>
    </div>

    <!--停用日期-->
    <div class="form-group row mb-3">
      <label class="col-sm-2 col-form-label text-sm-right  text-sm-end">
        <{$smarty.const._MA_JILLBOOKIN_JBI_END}>
      </label>
      <div class="col-sm-6">
        <input type="text" name="jbi_end" id="jbi_end" class="form-control" value="<{$jbi_end|default:''}>"  onClick="WdatePicker({dateFmt:'yyyy-MM-dd' , startDate:'%y-%M-%d'})" placeholder="<{$smarty.const._MA_JILLBOOKIN_JBI_END}>">
      </div>
    </div>

    <!--場地排序-->
    <!-- <div class="form-group row mb-3">
      <label class="col-sm-2 col-form-label text-sm-right  text-sm-end">
        <{$smarty.const._MA_JILLBOOKIN_JBI_SORT}>
      </label>
      <div class="col-sm-2">
        <input type="text" name="jbi_sort" id="jbi_sort" class="form-control " value="<{$jbi_sort|default:''}>" placeholder="<{$smarty.const._MA_JILLBOOKIN_JBI_SORT}>">
      </div>
    </div> -->

    <!--是否可借-->
    <div class="form-group row mb-3">
      <label class="col-sm-2 col-form-label text-sm-right  text-sm-end">
        <{$smarty.const._MA_JILLBOOKIN_JBI_ENABLE}>
      </label>
      <div class="col-sm-10">

        <div class="form-check form-check-inline">
          <input class="form-check-input" id="jbi_enable_1" type="radio" name="jbi_enable"  value="1" <{if $jbi_enable == "1"}>checked="checked"<{/if}>>
          <label class="form-check-label" for="jbi_enable_1"><{$smarty.const._YES}></label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" id="jbi_enable_0" type="radio" name="jbi_enable" value="0" <{if $jbi_enable != "1"}>checked="checked"<{/if}>>
          <label class="form-check-label" for="jbi_enable_0"><{$smarty.const._NO}></label>
        </div>
        <input type='hidden' name="jbi_sn" value="<{$jbi_sn|default:''}>">

        <{$token_form|default:''}>

        <input type="hidden" name="op" value="<{$next_op|default:''}>">
        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
      </div>
    </div>


    <!--場地說明-->
    <div class="form-group row mb-3">
      <label class="col-sm-2 col-form-label text-sm-right  text-sm-end">
        <{$smarty.const._MA_JILLBOOKIN_JBI_DESC}>
      </label>
      <div class="col-sm-10">
        <{$Editor_code|default:''}>
      </div>
    </div>

  </form>
</div>