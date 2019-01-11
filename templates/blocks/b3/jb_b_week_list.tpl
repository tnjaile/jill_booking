<script>
  $(document).ready(function(){
    //$('.users').popover({placement:'top',html:'true',trigger:'hover'});--bootstrap3 method
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
  });
  </script>
<style type="text/css" media="screen">
  .resp-vtabs .resp-tabs-container{
    min-height: <{$block.height}>px;
  }
</style>
<div id="iteamtab<{$block.randStr}>" >
  <ul class="resp-tabs-list vert" >
    <{foreach from=$block.content  item=data}>
      <li><{$data.jbi_title}></li>
    <{/foreach}>
  </ul>
    <div class="resp-tabs-container vert">
      <{foreach from=$block.content item=data}>
        <div>
          <table class="table table-condensed table-striped table-hover table-bordered table-responsive">
           <thead>
            <tr style="background-color: #5AB1D0;">
               <th style="text-align:center;">
                 <div><{$smarty.now|date_format:'%Y'}></div>
                 <div><{$smarty.const._MB_JSESSION}></div>                   
               </th>
                 <{foreach from=$block.weekArr item=week}>
                    <th style="text-align:center;">
                      <div style='text-align: center; vertical-align: middle; font-size: 1.2em;'><{$week.d|date_format:'%m/%d'}></div>
                      <div style='text-align: center; vertical-align: middle; font-size: 0.8em;'><{$week.w}></div>
                    </th>
                 <{/foreach}>
            </tr>
           </thead>
           <tbody>
            <{if $data.timeArr}>
              <{foreach from=$data.timeArr key=t item=time}>
               <tr >
                 <td style="text-align:center;vertical-align: middle;"><{$time.jbt_title}></td>
                 <{foreach from=$block.weekArr key=wk item=week}>
                  <td>
                    <div  id="submit<{$t}>_<{$wk}>" style='text-align: center; vertical-align: middle; color:<{$data.bookingArr.$t.$wk.color}>;'>                      
                        <{$data.bookingArr.$t.$wk.content}>
                    </div>
                  </td>
                 <{/foreach}>
               </tr>
             <{/foreach}>
            <{/if}>
           </tbody>
          </table>            
        </div>
      <{/foreach}>
    </div>
</div>
<div class="row" style="margin: 10px 0px;text-align: right;">
  <a href="<{$xoops_url}>/modules/jill_booking/index.php" class="btn btn-xs btn-info pull-right"><{$smarty.const._MB_JILLBOOKIN_MORE}></a>
</div>