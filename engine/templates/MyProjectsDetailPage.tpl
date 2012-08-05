{extends file="InfoBasePage.base.tpl"}

{block name=script}
{literal}
    $('.reports_form').checkboxes({titleOn: "Отметить всё", titleOff: "Снять отметки"});

    $('#delete_member').click(function(){
    return confirm('Вы действительно желаете удалить выделенных участников?');
    });

    $("#item_kind, #project_id").change(function(){
    $("#selectProjectForm").submit();
    });
{/literal}
{/block}

{block name=body}
{if $GOOD eq TRUE}
    <div class="alert alert-success">
        <a class="close" data-dismiss="alert" href="#">&times;</a>
        <span>Проект успешно создан</span>
    </div>
{/if}
<div class="btn-toolbar">
    <div class="btn-group ">
        {if $ITEM_PREV_ID neq NULL}
            <a class="btn" href="/my/project/show/{$ITEM_PREV_ID}" rel="tooltip" title="К предыдущему элементу">
                <i class="icon-arrow-left"></i>
            </a>
        {else}
            <button class="btn" disabled="disabled" rel="tooltip" title="К предыдущему элементу">
                <i class="icon-arrow-left"></i>
            </button>
        {/if}
        {if $ITEM_NEXT_ID neq NULL}
            <a class="btn" href="/my/project/show/{$ITEM_NEXT_ID}" rel="tooltip" title="К следующему элементу">
                <i class="icon-arrow-right"></i>
            </a>
        {else}
            <button class="btn" disabled="disabled" rel="tooltip" title="К следующему элементу">
                <i class="icon-arrow-right"></i>
            </button>
        {/if}
    </div>
    <div class="btn-group">
        <a class="btn btn-primary" href="/my/project/new/">
            <i class="icon-asterisk icon-white"></i>
        </a>
        <a class="btn btn-danger" href="/my/project/delete/{$Project.ProjectID}/">
            <i class="icon-trash icon-white"></i>
        </a>
    </div>
</div>
<ul class="nav nav-tabs" id="item-tab">
    <li class="active"><a href="#about" data-toggle="tab">Описание</a></li>
    <li><a href="#users" data-toggle="tab">Участники <span class="label label-success">{$PROJECT_USERS_COUNT}</span></a></li>
    {if $IS_OWNER eq true}
        <li><a href="#requests" data-toggle="tab">Заявки{if $COUNT_SUBSCRIBES neq 0} <span class="label label-warning">{$COUNT_SUBSCRIBES}</span>{/if}</a></li>	
    {/if}
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="about">
        <form class="form-horizontal" action="/my/project/edit/" method="post">
            <fieldset {if $IS_OWNER eq false}disabled="disabled"{/if}>
                <legend>Редактирование проекта</legend>
                <div class="control-group">
                    <label class="control-label" for="author">Автор</label>
                    <div class="controls">
                        <label><a href="/profile/show/{$Project.OwnerID}/">{$Project.OwnerNickName}</a></label>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="project_name">Имя проекта</label>
                    <div class="controls">
                        <input type="text" {if $IS_OWNER eq false}disabled="disabled"{/if} class="input-xlarge" name="project_name" id="project_name" value="{$Project.Name}" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="description">Описание проекта</label>
                    <div class="controls">
                        <textarea name="description" {if $IS_OWNER eq false}disabled="disabled"{/if} id="description" class="input-xxlarge" rows="10">{$Project.Description}</textarea>
                    </div>
                </div>
                <div class="form-actions">
                    <input class="btn btn-primary" type="submit" {if $IS_OWNER eq false}disabled="disabled"{/if} value="Сохранить изменения" name="save" />
                </div>
                <input type="hidden" name="project_id" value="{$Project.ProjectID}" />	
            </fieldset>
        </form>
    </div>
    <div class="tab-pane" id="users">
        {if $MY_PROJECT_DETAIL_PAGINATOR neq NULL}
            <div class="pagination pagination-right"> 
                {$MY_PROJECT_DETAIL_PAGINATOR}
            </div>
        {/if}
        {if $PROJECT_USERS neq NULL}
            <form action="#users" class="reports_form" method="post"> 
                <table class="table table-bordered table-striped checkbox-table"> 
                    <thead> 
                        <tr> 
                            {if $IS_OWNER eq true}
                                <th><input name="del" type="checkbox" /></th> 
                                {/if}
                            <th><a href="{$MY_PROJECT_ORDERER.NickName.url}#users" {if $MY_PROJECT_ORDERER.NickName.order eq true}class="sort"{/if}>Пользователь</a></th> 
                            <th><a href="{$MY_PROJECT_ORDERER.CountCreated.url}#users" {if $MY_PROJECT_ORDERER.CountCreated.order eq true}class="sort"{/if}>Создано</a></th> 
                            <th><a href="#">Количество комментариев</a></th> 
                            <th colspan="5"><a href="{$MY_PROJECT_ORDERER.CountErrors.url}#users" {if $MY_PROJECT_ORDERER.CountErrors.order eq true}class="sort"{/if}>Назначенные</a></th> 
                        </tr> 
                    </thead> 
                    <tbody> 
                        {foreach name=projectUsers from=$PROJECT_USERS item=element} {* Выводит мои проекты*}
                            <tr class="{if $smarty.foreach.projectUsers.index % 2 == 0}odd{else}even{/if}"> 
                                {if $IS_OWNER eq true}
                                    <td><input name="del_i[{$element.UserID}]" type="checkbox" {if $element.Owner eq 1}disabled="disabled"{/if} /></td> 
                                    {/if}
                                <td>{if $element.Owner eq 1}<a href="/profile/show/{$element.UserID}/">{$element.NickName}</a>&nbsp;<span class="label label-success">Владелец</span>{else}<a href="/profile/show/{$element.UserID}/">{$element.NickName}</a>{/if}</td> 
                                <td>{$element.CountCreated}</td> 
                                <td>{$element.CountComments}</td> 
                                <td class="item-state-new">{$element.NEW}</td><td class="item-state-assesed">{$element.IDENTIFIED}</td><td class="item-state-inprocess">{$element.ASSESSED}</td><td class="item-state-solved">{$element.RESOLVED}</td><td class="closed">{$element.CLOSED}</td>
                            </tr> 
                        {/foreach}
                    </tbody> 
                </table> 
                {if $IS_OWNER eq true}
                    <div class="btn-toolbar"> 
                        <button class="btn btn-danger" type="submit" name="delete_member" id="delete_member" title="Удалить выделенные">
                            <i class="icon-trash icon-white"></i>
                            Удалить выделенных участников
                        </button>
                    </div> 
                {/if}
            </form> 
        {/if} 
    </div>
    {if $IS_OWNER eq true}
        <div class="tab-pane" id="requests"> 
            {if $PROJECT_SUBSCRIBES_REQUEST_PAGINATOR neq NULL}
                <div class="pagination pagination-right"> 
                    {$PROJECT_SUBSCRIBES_REQUEST_PAGINATOR}
                </div>
            {/if}
            {if $PROJECT_SUBSCRIBES_REQUEST neq NULL}
                <form action="#requests" class="reports_form" method="post"> 
                    <table class="table table-bordered table-striped checkbox-table"> 
                        <col width="23" /> 
                        <thead> <tr> 
                                <th><input name="del" type="checkbox" /></th> 
                                <th><a href="{$MY_PROJECT_ORDERER.NickName.url}#users" {if $MY_PROJECT_ORDERER.NickName.order eq true}class="sort"{/if}>Пользователь</a></th> 
                                <th><a href="{$MY_PROJECT_ORDERER.CountCreated.url}#users" {if $MY_PROJECT_ORDERER.CountCreated.order eq true}class="sort"{/if}>Дата заявки</a></th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            {foreach name=projectSubscribes from=$PROJECT_SUBSCRIBES_REQUEST item=element} {* Выводит мои проекты*}
                                <tr class="{if $smarty.foreach.projectUsers.index % 2 == 0}odd{else}even{/if}"> 
                                    <td><input name="sub_i[{$element.SubscribeRequestID}]" type="checkbox" /></td> 
                                    <td>{if $element.Owner eq 1}<strong><a href="/profile/show/{$element.UserID}/">{$element.NickName}</a></strong>&nbsp;<sup style="font-size: 10px; color: red;">(владелец)</sup>{else}<a href="/profile/show/{$element.UserID}/">{$element.NickName}</a>{/if}</td> 
                                    <td>{$element.RequestTime}</td> 
                                </tr> 
                            {/foreach}
                        </tbody> 
                    </table> 
                    <div class="btn-toolbar"> 
                        <button class="btn btn-primary" type="submit" name="assign_subscribes" id="assign_subscribes" title="Удалить выделенные">
                            <i class="icon-ok icon-white"></i>
                            Подтвердить заявки
                        </button>
                        <button class="btn btn-danger" type="submit" name="delete_subscribes" id="delete_subscribes" title="Удалить выделенные">
                            <i class="icon-trash icon-white"></i>
                            Удалить заявки
                        </button>
                    </div> 
                </form> 
            {/if} 
        </div> 
    {/if}
</div>

{/block}