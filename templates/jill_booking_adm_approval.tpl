<!--顯示表單-->
<{if $now_op=="jbi_approval_form"}>
  <script type="text/javascript" src="<{$xoops_url}>/modules/jill_booking/class/tmt_core.js"></script>
  <script type="text/javascript" src="<{$xoops_url}>/modules/jill_booking/class/tmt_spry_linkedselect.js"></script>
  <script type='text/javascript'>
    function gettmtOptions()
    {
      var x=document.getElementById('destination');
      txt='';
      for (i=0;i<x.length;i++)
      {
        if(i==0){
          txt=x.options[0].value;
        }
        else{
          txt=txt + ';' + x.options[i].value;
        }

      }
      document.getElementById('jbi_approval').value=txt;
    }
  </script>
  <h2 class="text-center"><{$jbi_title|default:''}></h2>
  <div class="container ">
    <form action="<{$action|default:''}>" method="post" id="myForm" enctype="multipart/form-data"  role="form">
      <!--場地編號-->
    <{$tmt_box|default:''}>
    <{$token_form|default:''}>
    <{*
      <input type='hidden' name="jbi_sn" value="<{$jbi_sn|default:''}>">
      <input type="hidden" name="op" value="<{$next_op|default:''}>">
      <input type='hidden' name='jbi_approval' id='jbi_approval'  value='<{$jbi_approval|default:''}>'>

      <div class="row">
        <div class="col-sm-5">
          <h3 class="text-center text-info">
            <{$smarty.const._MA_JILLBOOKIN_ALLUSERS}>
          </h3>
          <select name='repository' id='repository' size='12' multiple='multiple' tmt:linkedselect='true' class='col-sm-12' title='repository'>
            <{foreach from=$all_content item=data}>
              <option value="<{$data.uid}>">
                <{$data.uname}>【<{$data.name}>】
              </option>
            <{/foreach}>
          </select>
        </div>
        <div class="col-sm-2" >
          <p class="lead" style="margin-top: 50px">
            <button type="button" class="btn  btn-block" onclick="tmt.spry.linkedselect.util.moveOptions('repository', 'destination');gettmtOptions();"><img src="../images/right.png"></button>
            <button type="button" class="btn  btn-block" onclick="tmt.spry.linkedselect.util.moveOptions('destination', 'repository');gettmtOptions();"><img src="../images/left.png"></button><br>
            <input type="hidden" name="op" value="<{$next_op|default:''}>">
            <button type="submit" class="btn btn-primary btn-block"><{$smarty.const._TAD_SAVE}></button>
          </p>
          <{$token_form|default:''}>
        </div>
        <div class="col-sm-5">
          <h3 class="text-info text-center">
            <{$smarty.const._MA_JILLBOOKIN_APPROVER}>
          </h3>
          <select name='destination' id='destination' size='12' multiple='multiple' tmt:linkedselect='true' class='col-sm-12' title='destination'>
            <{if $all_content2|default:false}>
              <{foreach from=$all_content2 item=data}>
                <option value="<{$data.uid}>">
                  <{$data.uname}>【<{$data.name}>】
                </option>
              <{/foreach}>
            <{/if}>
          </select>
        </div>
      </div>
      *}>
    </form>
  </div>
<{/if}>
