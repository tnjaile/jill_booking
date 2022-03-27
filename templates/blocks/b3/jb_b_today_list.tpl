<style type="text/css" media="screen">
  .resp-vtabs .resp-tabs-container{
    min-height: <{$block.height}> px;
  }
  .row{
    margin-bottom: 1rem;
  }
</style>
<div class="row text-right">
  <a href="<{$xoops_url}>/modules/jill_booking/index.php" class="btn btn-xs btn-info pull-right"><{$smarty.const._MB_JILLBOOKIN_MORE}></a>
</div>
<div id="iteamtab<{$block.randStr}>"  class="row">
  <ul class="resp-tabs-list vert" >
    <{foreach from=$block.content item=data}>
      <li><{$data.jbi_title}></li>
    <{/foreach}>
  </ul>
    <div class="resp-tabs-container vert">
      <{foreach from=$block.content item=data}>
        <div class="table-responsive">
          <table class="table table-condensed table-striped table-hover table-bordered">
            <thead>
              <tr style="background-color: #5AB1D0;">
                <th><{$smarty.const._MB_JSESSION}></th>
                <th><{$smarty.const._MB_JILLBOOKIN_JB_UID}></th>
              </tr>
            </thead>
            <tbody>
              <{if $data.todaylist}>
                <{foreach from=$data.todaylist item=tlist}>
                  <tr>
                    <td><{$tlist.jbt_title}></td>
                    <td><{$tlist.name}></td>
                  </tr>
                <{/foreach}>
              <{/if}>
            </tbody>
          </table>
        </div>
      <{/foreach}>
    </div>
</div>