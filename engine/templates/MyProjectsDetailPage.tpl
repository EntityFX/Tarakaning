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
<div id="content_body">
	{if $GOOD eq TRUE}
	<div class="messageBox goodBox">
		<strong class="ok" id="good">Проект успешно создан</strong>
	</div> 
	{/if}
	<div id="tabs"> 
		<ul> 
			<li><a href="#about"><span>Описание</span></a></li> 
			<li><a href="#users"><span>Участники</span></a></li> 
			{if $IS_OWNER eq true}
			<li><a href="#requests"><span>Заявки</span>{if $COUNT_SUBSCRIBES neq 0} <strong class="strongest">({$COUNT_SUBSCRIBES})</strong>{/if}</a></li>	
			{/if}		
		</ul> 
		<div id="about" class="info_div">			
			<dl> 
				<dt>Название</dt> 
				<dd>{$Project.Name}</dd> 
				<dt>Автор</dt> 
				<dd><a href="/profile/show/{$Project.OwnerID}/">{$Project.OwnerNickName}</a></dd>				
				<dt>Описание</dt> 
				<dd>{$Project.Description}&nbsp;</dd> 
			</dl> 
			{if $IS_OWNER eq true}
			<form action="/my/project/edit/" method="post">
				<div>
					<input type="submit" value="Редактировать проект" />
					<input type="hidden" name="project_id" value="{$Project.ProjectID}" />
				</div>
			</form>
			{/if} 
		</div> 
		<div id="users"> 
			{if $MY_PROJECT_DETAIL_PAGINATOR neq NULL}
			<div class="groupier"> 
				{$MY_PROJECT_DETAIL_PAGINATOR}
			</div>
			{/if}
			{if $PROJECT_USERS neq NULL}
			<form action="#users" class="reports_form" method="post"> 
				<table class="projects_table"> 
					<col width="23" /> 
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
							<td>{if $element.Owner eq 1}<strong><a href="/profile/show/{$element.UserID}/">{$element.NickName}</a></strong>&nbsp;<sup style="font-size: 10px; color: red;">(владелец)</sup>{else}<a href="/profile/show/{$element.UserID}/">{$element.NickName}</a>{/if}</td> 
							<td>{$element.CountCreated}</td> 
							<td>{$element.CountComments}</td> 
							<td class="new">{$element.NEW}</td><td class="confirmed">{$element.IDENTIFIED}</td><td class="assigned">{$element.ASSESSED}</td><td class="solved">{$element.RESOLVED}</td><td class="closed">{$element.CLOSED}</td>
						</tr> 
					{/foreach}
					</tbody> 
				</table> 
				{if $IS_OWNER eq true}
				<div class="groupier"> 
					<input value="Удалить выделенных подписчиков" name="delete_member" id="delete_member" type="submit" /> 
				</div> 
				{/if}
			</form> 
			{/if} 
		</div> 
		{if $IS_OWNER eq true}
		<div id="requests"> 
			{if $PROJECT_SUBSCRIBES_REQUEST_PAGINATOR neq NULL}
			<div class="groupier"> 
				{$PROJECT_SUBSCRIBES_REQUEST_PAGINATOR}
			</div>
			{/if}
			{if $PROJECT_SUBSCRIBES_REQUEST neq NULL}
			<form action="#requests" class="reports_form" method="post"> 
				<table class="projects_table"> 
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
							<td><input name="sub_i[{$element.ID}]" type="checkbox" /></td> 
							<td>{if $element.Owner eq 1}<strong><a href="/profile/show/{$element.UserID}/">{$element.NickName}</a></strong>&nbsp;<sup style="font-size: 10px; color: red;">(владелец)</sup>{else}<a href="/profile/show/{$element.UserID}/">{$element.NickName}</a>{/if}</td> 
							<td>{$element.RequestTime}</td> 
						</tr> 
					{/foreach}
					</tbody> 
				</table> 
				<div class="groupier"> 
					<input value="Подтвердить заявки" name="assign_subscribes" id="assign_subscribes" type="submit" /> 
					<input value="Удалить заявки" name="delete_subscribes" id="delete_subscribes" type="submit" /> 
				</div> 
			</form> 
			{/if} 
		</div> 
		{/if}
    </div> 
</div>
{/block}