<{$toolbar|default:''}>
<h3 class='sr-only'>批次預約</h3>
<{if $smarty.session.can_booking|default:false}>
  <!--顯示表單-->
  <script type="text/javascript" src="<{$xoops_url}>/modules/tadtools/My97DatePicker/WdatePicker.js"></script>
  <{if $now_op=="jill_booking_form"}>
    <!--選擇場地-->
    <select name="jbi_sn" id="jbi_sn" class="form-control form-select mb-3" style="width: auto;" onChange="location.href='<{$action|default:''}>?jbi_sn='+this.value" title='jbi_sn'>
      <option value=""><{$smarty.const._MD_JILLBOOKIN_CHOOSEITEM}></option>
      <{$item_opt|default:''}>
    </select>

    <{if $itemArr.jbi_sn|default:false}>
      <!--套用formValidator驗證機制-->
      <{$formValidator_code|default:''}>
      <h3 class="text-center"><{$itemArr.jbi_title}></h3>
      <hr>
      <form action="<{$action|default:''}>" method="post" id="myForm" role="form">
        <!--起訖日期-->
        <div class="row  mb-2">
          <label class="col-sm-2 col-form-label text-md-right  text-md-end ">
            <{$smarty.const._MD_JILLBOOKIN_JB_DATE}>
          </label>
          <div class="col-sm-10">
            <div class="row">
                <div class="col-sm-5">
                  <input type="text" name="jb_start_date" id="jb_start_date" class="form-control" value="<{$jb_start_date|default:''}>"  onClick="WdatePicker({dateFmt:'yyyy-MM-dd' , startDate:'%y-%M-%d',minDate:'<{$jb_start_date|default:''}>'<{if $max_date|default:false}>,maxDate:'<{$max_date|default:''}>'<{/if}>})" placeholder="<{$smarty.const._MD_JILLBOOKIN_JB_START_DATE}>">
                </div>
                <div class="col-sm-2 text-center"><i class="fa fa-arrow-right fa-2x"></i>
                </div>
                <div class="col-sm-5">
                  <input type="text" name="jb_end_date" id="jb_end_date" class="form-control" value="<{$jb_end_date|default:''}>"  onClick="WdatePicker({dateFmt:'yyyy-MM-dd' , startDate:'%y-%M-%d',minDate:'<{$jb_end_date|default:''}>'<{if $max_date|default:false}>,maxDate:'<{$max_date|default:''}>'<{/if}>})" placeholder="<{$smarty.const._MD_JILLBOOKIN_JB_END_DATE}>">
                </div>
            </div>
          </div>
        </div>
        <!--預約理由-->
        <div class="row mb-2">
          <label class="col-sm-2 col-form-label text-md-right  text-md-end">
            <{$smarty.const._MD_JILLBOOKIN_JB_BOOKING_CONTENT}>
          </label>
          <div class="col-sm-10">
            <input type="text"  name="jb_booking_content"  id="jb_booking_content" class="form-control" placeholder="<{$smarty.const._MD_JILLBOOKIN_JB_BOOKING_CONTENT}>"><{$jb_booking_content|default:''}>
          </div>
        </div>

        <!--星期/時段-->
        <div class="row mb-2">
          <label class="col-sm-2 col-form-label text-md-right  text-md-end">
            <{$smarty.const._MD_TIME_WEEK}>
          </label>
          <div class="col-sm-10" style="background-color: #F3F3F7;">
            <table class="table table-striped table-hover table-sm">
              <thead>
                <!--時段標題-->
                <th class="th_style"></th>
                <th class="th_style"><!--星期日--><{$smarty.const._MD_JILLBOOKIN_W0}></th>
                <th class="th_style"><!--星期一--><{$smarty.const._MD_JILLBOOKIN_W1}></th>
                <th class="th_style"><!--星期二--><{$smarty.const._MD_JILLBOOKIN_W2}></th>
                <th class="th_style"><!--星期三--><{$smarty.const._MD_JILLBOOKIN_W3}></th>
                <th class="th_style"><!--星期四--><{$smarty.const._MD_JILLBOOKIN_W4}></th>
                <th class="th_style"><!--星期五--><{$smarty.const._MD_JILLBOOKIN_W5}></th>
                <th class="th_style"><!--星期六--><{$smarty.const._MD_JILLBOOKIN_W6}></th>
              </thead>
              <tbody id="sort">
                <{if $weektime|default:false}>
                  <{foreach from=$timeArr key=t item=time}>
                    <tr id='tr_<{$data.jbt_sn}>'>
                      <td  class="th_style"><{$time.jbt_title}></td>
                      <{section name=w start=0 loop=7}>
                        <{assign var="wk" value=$smarty.section.w.index}>
                        <td style="text-align: center; vertical-align: middle;"><{$weektime.$t.$wk.jbt_week}></td>
                      <{/section}>
                    </tr>
                  <{/foreach}>
                <{/if}>
              </tbody>
            </table>
          </div>
        </div>
        <div class="text-center">

        <!--預約編號-->
        <input type='hidden' name="jbi_sn" value="<{$itemArr.jbi_sn}>">
        <input type='hidden' name="max_date" value="<{$max_date|default:''}>">

          <{$token_form|default:''}>

          <input type="hidden" name="op" value="<{$next_op|default:''}>">
          <button type="submit" class="btn btn-primary"><{$smarty.const._MD_SUMIT}></button>
        </div>
      </form>
    <{/if}>
  <{/if}>

  <{if $now_op=="list_jill_booking"}>
    <style>
      #css_table {
        display:table;
        border-collapse: collapse;
        border-spacing: 0;
      }
      #css_tr {
        display: table-row;
      }
      #css_td,#css_th {
        display: table-cell;
        text-align: center;
        vertical-align: middle;
        border: 1px solid black;
        padding: 4px 5px;
        line-height: 1.5em;
      }
      #css_th {
        background-color: #a2ccee;
        font-weight: bold;
      }
    </style>
    <h2 class="text-info"><{$jbi_title|default:''}></h2>
    <form action="<{$action|default:''}>" method="post" id="myForm" enctype="multipart/form-data"  role="form">
      <div id="css_table" >
        <div id="css_th"><{$smarty.const._MD_JBOOKING}></div>
        <div id="css_th"><{$smarty.const._MD_JDATE}></div>
        <div id="css_th"><{$smarty.const._MD_JWEEK}></div>
        <div id="css_th"><{$smarty.const._MD_JSESSION}></div>
        <{if $maxwaiting|default:false}>
          <{section name=w start=1 loop=$maxwaiting+1 step=1}>
            <div id="css_th"><{$smarty.const._MD_JILLBOOKIN_JB_UID}><{$smarty.section.w.index}></div>
          <{/section}>
        <{else}>
          <div id="css_th"><{$smarty.const._MD_JILLBOOKIN_JB_UID}>1</div>
        <{/if}>
          <{foreach from=$dateweek  item=dw}>
            <div id="css_tr">
              <div id="css_td">
                <input type="checkbox" name="jb_date[<{$dw.jbt_sn}>][]"  value="<{$dw.jb_date}>" <{if $dw.jb_exit == "0"}>checked="checked"<{/if}> >
              </div>
              <div id="css_td"><{$dw.jb_date}></div>
              <div id="css_td">
                <{if $dw.week=="0"}><{$smarty.const._MD_JILLBOOKIN_W0}>
                <{elseif $dw.week=="1"}><{$smarty.const._MD_JILLBOOKIN_W1}>
                <{elseif $dw.week=="2"}><{$smarty.const._MD_JILLBOOKIN_W2}>
                <{elseif $dw.week=="3"}><{$smarty.const._MD_JILLBOOKIN_W3}>
                <{elseif $dw.week=="4"}><{$smarty.const._MD_JILLBOOKIN_W4}>
                <{elseif $dw.week=="5"}><{$smarty.const._MD_JILLBOOKIN_W5}>
                <{else }><{$smarty.const._MD_JILLBOOKIN_W6}>
                <{/if}>
              </div>
              <div id="css_td"><{$dw.jbt_title}></div>
              <{foreach from=$dw.waitingArr item=w}>
                <div id="css_td"><{$w.name}></div>
              <{/foreach}>
            </div>
          <{/foreach}>
      </div>

      <div class="text-center">
        <{$token_form|default:''}>
        <input type="hidden" name="op" value="<{$next_op|default:''}>">
        <input type="hidden" name="jb_sn" value="<{$jb_sn|default:''}>">
        <input type="hidden" name="jbi_sn" value="<{$jbi_sn|default:''}>">
        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
      </div>
    </form>
  <{/if}>

<{else}>
  <div class="jumbotron">
    <p class="alert alert-error"><{$smarty.const._MD_JBOOKING_ERROR}></p>
  </div>
<{/if}>
