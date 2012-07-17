{extends file="BugsBasePage.tpl"}
{block name=bugs_block}
<ul class="nav nav-tabs" id="item-tab">
    <li class="active"><a href="#my-items" data-toggle="tab">Мои</a></li>
    <li><a href="#assigned-items" data-toggle="tab">Назначеннные</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="my-items"> 
        {if $MY_BUGS neq NULL}
            <div class="pagination pagination-right">
                {$MY_PROJECT_BUGS_PAGINATOR}    
            </div>
            <form method="post" class="reports_form" action="#">
                <table class="table table-bordered table-striped checkbox-table">
                    <thead>
                        <tr>
                            <th><input name="del_all" type="checkbox" title="" /></th>
                            <th><a href="{$MY_BUGS_ORDERER.ID.url}" {if $MY_BUGS_ORDERER.ID.order eq true}class="sort"{/if}>№</a></th>
                            <th><a href="{$MY_BUGS_ORDERER.Kind.url}" {if $MY_BUGS_ORDERER.Kind.order eq true}class="sort"{/if}>Тип</a></th>
                            <th><a href="{$MY_BUGS_ORDERER.Status.url}" {if $MY_BUGS_ORDERER.Status.order eq true}class="sort"{/if}>Статус</a></th>
                            <th><a href="{$MY_BUGS_ORDERER.Title.url}" {if $MY_BUGS_ORDERER.Title.order eq true}class="sort"{/if}>Заголовок</a></th>
                            <th><a href="{$MY_BUGS_ORDERER.PriorityLevel.url}" {if $MY_BUGS_ORDERER.PriorityLevel.order eq true}class="sort"{/if}>Приоритет</a></th>
                            <th><a href="{$MY_BUGS_ORDERER.AssignedNickName.url}" {if $MY_BUGS_ORDERER.AssignedNickName.order eq true}class="sort"{/if}>Назначена</a></th>
                            <th colspan="2">Время (ч)</th>
                            <th style="width: 180px;"><a href="{$MY_BUGS_ORDERER.CreateDateTime.url}" {if $MY_BUGS_ORDERER.CreateDateTime.order eq true}class="sort"{/if}>Дата</a></th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach name=myBugs from=$MY_BUGS item=element} {* Выводит мои проекты*}
                            <tr>
                                <td><input name="del_i[{$element.ID}]" type="checkbox" /></td>
                                <td><a href="/bug/show/{$element.ID}/" class="sort">{$element.ID}</a></td>
                                <td>{$element.KindN}</td>
                                <td>{$element.StatusN}</td>
                                <td>{$element.Title}</td>
                                <td>{$element.PriorityLevelN}</td>
                                <td>{if $element.AssignedTo neq null}<a href="/profile/show/{$element.AssignedTo}/">{$element.AssignedNickName}</a>{/if}</td>
                                <td>{$element.HoursRequired}</td><td>{$element.HoursFact}</td>
                                <td>{$element.CreateDateTime}</td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
                <div class="btn-toolbar">
                    <input class="btn btn-danger" type="submit" id="del" name="del" title="Удалить выделенные" value="Удалить выделенные" />
                </div>
            </form>
        {else}
            <strong>Элементов нет</strong>
        {/if} 
    </div>
    <div class="tab-pane" id="assigned-items">
        {if $MY_ASSIGNED_BUGS neq NULL}
            <div class="pagination pagination-right">
                {$MY_ASSIGNED_BUGS_PAGINATOR}
            </div>
            <form action="#" class="reports_form" method="post">
                <!--<a class="z" href="#">Выбрать всё</a>-->
                <table class="table table-bordered table-striped checkbox-table">
                    <thead>
                        <tr>
                            <th><input name="del_all" type="checkbox" title="" /></th>
                            <th><a href="{$MY_ASSIGNED_BUGS_ORDERER.ID.url}#assigned_items" {if $MY_ASSIGNED_BUGS_ORDERER.ID.order eq true}class="sort"{/if}>№</a></th>
                            <th><a href="{$MY_ASSIGNED_BUGS_ORDERER.Kind.url}#assigned_items" {if $MY_ASSIGNED_BUGS_ORDERER.Kind.order eq true}class="sort"{/if}>Тип</a></th>
                            <th><a href="{$MY_ASSIGNED_BUGS_ORDERER.Status.url}#assigned_items" {if $MY_ASSIGNED_BUGS_ORDERER.Status.order eq true}class="sort"{/if}>Статус</a></th>
                            <th><a href="{$MY_ASSIGNED_BUGS_ORDERER.Title.url}#assigned_items" {if $MY_ASSIGNED_BUGS_ORDERER.Title.order eq true}class="sort"{/if}>Заголовок</a></th>
                            <th><a href="{$MY_ASSIGNED_BUGS_ORDERER.PriorityLevel.url}#assigned_items" {if $MY_ASSIGNED_BUGS_ORDERER.PriorityLevel.order eq true}class="sort"{/if}>Приоритет</a></th>
                            <th><a href="{$MY_ASSIGNED_BUGS_ORDERER.AssignedNickName.url}#assigned_items" {if $MY_ASSIGNED_BUGS_ORDERER.AssignedNickName.order eq true}class="sort"{/if}>Назначена</a></th>
                            <th colspan="2">Время (ч)</th>
                            <th style="width: 180px;"><a href="{$MY_ASSIGNED_BUGS_ORDERER.Time.url}#assigned_items" {if $MY_ASSIGNED_BUGS_ORDERER.Time.order eq true}class="sort"{/if}>Дата</a></th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach name=myAssignedBugs from=$MY_ASSIGNED_BUGS item=element} {* Выводит мои проекты*}
                            <tr class="{bug_type value=$element.Status}">
                                <td><input name="del_i[{$element.ID}]" type="checkbox" /></td>
                                <td><a href="/bug/show/{$element.ID}/" class="sort">{$element.ID}</a></td>
                                <td>{$element.KindN}</td>
                                <td>{$element.StatusN}</td>
                                <td>{$element.Title}</td>
                                <td>{$element.PriorityLevelN}</td>
                                <td>{if $element.AssignedTo neq null}<a href="/profile/show/{$element.AssignedTo}/">{$element.AssignedNickName}</a>{/if}</td>
                                <td>{$element.HoursRequired}</td><td>{$element.HoursFact}</td>
                                <td>{$element.CreateDateTime}</td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
                <div class="toolbar">
                    <input type="submit" class="btn btn-danger" value="Удалить выделенные" title="Удалить выделенные" name="del_assigned" id="del_assigned" />
                </div>
            </form>
        {else}
            <strong>Элементов нет</strong>
        {/if}



    </div>
    {/block}

