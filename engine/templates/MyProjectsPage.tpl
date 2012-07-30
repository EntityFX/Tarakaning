{extends file="InfoBasePage.base.tpl"}

{block name=script}
{literal}
    $('.reports_form').checkboxes({titleOn: "Отметить всё", titleOff: "Снять отметки"});
    $('#del').click(function(){
    return confirm('Вы действительно желаете удалить проекты? Это приведёт к удалению всех задач, комментариев, а также подписчиков');
    });

    $("#item_kind, #project_id").change(function(){
    $("#selectProjectForm").submit();
    });
{/literal}
{/block}

{block name=body}
<ul class="nav nav-tabs" id="item-tab">
    <li class="active"><a href="#my-projects" data-toggle="tab">Мои проекты</a></li>
    <li><a href="#all-projects" data-toggle="tab">Участвую в проектах</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="my-projects">  

        {if $MY_PROJECTS neq NULL}
            <div class="row">
                <form action="#" class="reports_form" method="post">
                    <div class="span12">
                        <table class="table table-bordered table-striped">
                            <thead> 
                                <tr>
                                    <th><input name="del" type="checkbox" /></th>
                                    <th><a href="{$MY_PROJECTS_ORDERER.ProjectName.url}" {if $MY_PROJECTS_ORDERER.ProjectName.order eq true}class="sort"{/if}>Проект</a></th>
                                    <th><a href="{$MY_PROJECTS_ORDERER.Description.url}" {if $MY_PROJECTS_ORDERER.Description.order eq true}class="sort"{/if}>Заголовок</a></th>
                                    <th colspan="5">Элементов</th>
                                    <th><a href="{$MY_PROJECTS_ORDERER.CountSubscribeRequests.url}" {if $MY_PROJECTS_ORDERER.CountSubscribeRequests.order eq true}class="sort"{/if}>Заявки</a></th>
                                    <th><a href="{$MY_PROJECTS_ORDERER.CountUsers.url}" {if $MY_PROJECTS_ORDERER.CountUsers.order eq true}class="sort"{/if}>Участников</a></th>
                                    <th><a href="{$MY_PROJECTS_ORDERER.CreateDateTime.url}" {if $MY_PROJECTS_ORDERER.CreateDateTime.order eq true}class="sort"{/if}>Дата создания</a></th>
                                </tr>
                            </thead> 
                            <tbody>
                                {foreach name=myProjects from=$MY_PROJECTS item=element} {* Выводит мои проекты*}
                                    <tr>
                                        <td><input name="del_i[{$element.ProjectID}]" type="checkbox" /></td>
                                        <td><a href="/my/project/show/{$element.ProjectID}/">{$element.ProjectName}</a><br />
                                        </td>
                                        <td>{$element.Description}</td>
                                        <td class="item-state-new">{$element.NEW}</td><td class="item-state-assesed">{$element.IDENTIFIED}</td><td class="item-state-inprocess">{$element.ASSESSED}</td><td class="item-state-solved">{$element.RESOLVED}</td><td class="closed">{$element.CLOSED}</td>
                                        <td><strong {if $element.CountSubscribeRequests neq 0}class="strongest"{/if}>{$element.CountSubscribeRequests}</strong></td>
                                        <td>{$element.CountUsers}</td>
                                        <td>{$element.CreateDateTime}</td>
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                    <div class="span6">
                        <button class="btn btn-danger" type="submit" id="del" name="del" title="Удалить выделенные">
                            <i class="icon-trash icon-white"></i>
                            Удалить выделенные проекты
                        </button>
                    </div>
                    <div class="span6">
                        <div class="pagination pagination-right pagination-custom">
                            {$MY_PROJECTS_PAGINATOR}
                        </div>
                    </div>
                </form>
            </div>
        {else}
            <div class="alert alert-info">
                <a class="close" data-dismiss="alert" href="#">&times;</a>
                <span>Проектов нет</span>
            </div>
        {/if}

    </div>
    <div class="tab-pane" id="all-projects">
        {if $PROJECTS_WITHOUT_ME neq NULL}
            <div class="pagination pagination-right">
                {$MEMBER_PROJECTS_PAGINATOR}
            </div>
            <table class="projects_table">
                <thead> 
                    <tr>
                        <th><a href="{$MEMBER_PROJECTS_ORDERER.ProjectName.url}#all_projects" {if $MEMBER_PROJECTS_ORDERER.ProjectName.order eq true}class="sort"{/if}>Проект</a></th>
                        <th><a href="{$MEMBER_PROJECTS_ORDERER.Description.url}#all_projects" {if $MEMBER_PROJECTS_ORDERER.Description.order eq true}class="sort"{/if}>Описание</a></th>
                        <th><a href="{$MEMBER_PROJECTS_ORDERER.OwnerNickName.url}#all_projects" {if $MEMBER_PROJECTS_ORDERER.OwnerNickName.order eq true}class="sort"{/if}>Владелец</a></th>
                        <th colspan="5">Отчётов</th>
                        <th><a href="{$MEMBER_PROJECTS_ORDERER.CountUsers.url}#all_projects" {if $MEMBER_PROJECTS_ORDERER.CountUsers.order eq true}class="sort"{/if}>Участников</a></th>   
                        <th><a href="{$MEMBER_PROJECTS_ORDERER.CreateDateTime.url}#all_projects" {if $MEMBER_PROJECTS_ORDERER.CreateDateTime.order eq true}class="sort"{/if}>Дата</a></th>
                    </tr>
                </thead> 
                <tbody>
                    {foreach name=notMyProjects from=$PROJECTS_WITHOUT_ME item=element} {* Выводит мои проекты*}
                        <tr>
                            <td><a href="/my/project/show/{$element.ProjectID}/">{$element.ProjectName}</a></td>
                            <td>{$element.Description}</td>
                            <td><a href="#">{$element.OwnerNickName}</a></td>
                            <td class="item-state-new">{$element.NEW}</td><td class="item-state-assesed">{$element.IDENTIFIED}</td><td class="item-state-inprocess">{$element.ASSESSED}</td><td class="item-state-solved">{$element.RESOLVED}</td><td class="closed">{$element.CLOSED}</td>
                            <td>{$element.CountUsers}</td> 
                            <td>{$element.CreateDateTime}</td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        {else}
            <div class="alert alert-info">
                <a class="close" data-dismiss="alert" href="#">&times;</a>
                <span>Проектов нет</span>
            </div>
        {/if}
    </div>
</div>
{/block}