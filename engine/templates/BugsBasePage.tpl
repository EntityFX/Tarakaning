{extends file="InfoBasePage.base.tpl"}

{block name=script}
{literal}
    $('.reports_form').checkboxes({titleOn: "Отметить всё", titleOff: "Снять отметки"});
    $('#del, del_assigned').click(function(){
        return confirm('Вы действительно желаете удалить выделенные элементы?');
    });

    $("#item_kind").change(function(){
        $("#item_kind_form").submit();
    });
{/literal}
{/block}

{block name=body}
{* define the function *}
{function name=bug_type}
{if $value eq NEW}new{else if $value eq IDENTIFIED}confirmed{else if $value eq ASSESSED}assigned{else if $value eq RESOLVED}solved{else if $value eq CLOSED}closed{/if}
{/function}
<div id="content_body">
    {if $PROJECTS.PROJECTS_LIST neq NULL}
        <div class="row-fluid tarakaning-toolbar">
            <div class="btn-toolbar">
                <div class="btn-group">
                    <form id="item_kind_form" class="form-inline" action="">
                        <select id="item_kind" name="item_kind">
                            {html_options values=$ITEM_KIND.values output=$ITEM_KIND.text selected=$ITEM_KIND.selected}
                        </select>
                    </form>
                </div>
                <div class="pagination pagination-right">
                    {$PROJECT_BUGS_PAGINATOR}
                </div>
            </div>
        </div>
        <div class="row-fluid">
            {block name=bugs_block}{/block}
        </div>
    {else}
        <strong>У вас нет проектов. Создайте или подпишитесь на проект.</strong>
    {/if}
</div>
{/block}