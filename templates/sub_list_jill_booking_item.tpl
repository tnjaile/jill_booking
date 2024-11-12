<{if $all_content|default:false}>
    <{if $smarty.session.jill_book_adm|default:false}>
        <{$delete_jill_booking_item_func|default:''}>
        <{$jill_booking_item_jquery_ui|default:''}>
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
        <tr class="bg-secondary">
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
            <{if $smarty.session.jill_book_adm|default:false}>
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

            <{if $smarty.session.jill_book_adm|default:false}>
                <td>
                <a href="javascript:delete_jill_booking_item_func(<{$data.jbi_sn}>);" class="btn btn-sm btn-danger"><i class="fa fa-times" aria-hidden="true"></i> <{$smarty.const._TAD_DEL}></a>
                <a href="<{$xoops_url}>/modules/jill_booking/admin/main.php?op=jill_booking_item_form&jbi_sn=<{$data.jbi_sn}>" class="btn btn-sm btn-warning"><{$smarty.const._TAD_EDIT}></a>
                <a href="<{$xoops_url}>/modules/jill_booking/admin/time.php?op=list_jill_booking_time&jbi_sn=<{$data.jbi_sn}>" class="btn btn-sm btn-success"><{$smarty.const._MA_JILLBOOKIN_SETTIME}></a>
                <a href="<{$xoops_url}>/modules/jill_booking/admin/approval.php?jbi_sn=<{$data.jbi_sn}>" class="btn btn-sm btn-primary"><{$smarty.const._MA_JILLBOOKIN_SETAPPROVAL}></a>
                <img src="<{$xoops_url}>/modules/tadtools/treeTable/images/updown_s.png" style="cursor: s-resize;margin:0px 4px;" alt="<{$smarty.const._TAD_SORTABLE}>" title="<{$smarty.const._TAD_SORTABLE}>">
                </td>
            <{/if}>
            </tr>
        <{/foreach}>
        </tbody>
    </table>


    <{if $smarty.session.jill_book_adm|default:false}>
        <div class="text-right text-end">
        <a href="<{$xoops_url}>/modules/jill_booking/admin/main.php?op=jill_booking_item_form" class="btn btn-info"><{$smarty.const._MA_JILLBOOKIN_ADD}></a>
        </div>
    <{/if}>

    <{$bar|default:''}>
    <{else}>
    <{if $smarty.session.jill_book_adm|default:false}>
        <div class="jumbotron text-center">
        <a href="<{$xoops_url}>/modules/jill_booking/admin/main.php?op=jill_booking_item_form" class="btn btn-info"><{$smarty.const._MA_JILLBOOKIN_ADD}></a>
        </div>
    <{/if}>
<{/if}>