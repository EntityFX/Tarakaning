{extends file="info.base.tpl"}
{block name=body}
<div id="content_body">
	<div id="tabs">
		<ul>
			<li><a href="#my_project"><span>Мои проекты</span></a></li>
			<li><a href="#all_projects"><span>Все проекты</span></a></li>			
		</ul>
		<div id="my_project">
			{if $GOOD eq TRUE}
				<strong class="ok" id="good">Проект успешно создан</strong>
			{/if}
			<div class="groupier">
				<a href="/my/project/new/">Создать новый проект</a>
				{$MY_PROJECTS_PAGINATOR}
			</div>
			{if $MY_PROJECTS neq NULL}
			<form action="#" class="reports_form">
				<table class="projects_table">
					<col width="23" />
					<thead> 
						<tr>
						  <th><input name="del" type="checkbox" /></th>
	
						  <th><a href="{$MY_PROJECTS_ORDERER.Name.url}" {if $MY_PROJECTS_ORDERER.Name.order eq true}class="sort"{/if}>Проект</a></th>
						  <th><a href="{$MY_PROJECTS_ORDERER.Description.url}" {if $MY_PROJECTS_ORDERER.Description.order eq true}class="sort"{/if}>Заголовок</a></th>
						  <th colspan="5">Отчётов</th>
						  <th><a href="{$MY_PROJECTS_ORDERER.CountRequests.url}" {if $MY_PROJECTS_ORDERER.CountRequests.order eq true}class="sort"{/if}>Заявки</a></th>
						  <th><a href="{$MY_PROJECTS_ORDERER.CountUsers.url}" {if $MY_PROJECTS_ORDERER.CountUsers.order eq true}class="sort"{/if}>Пользователей</a></th>
						  <th><a href="{$MY_PROJECTS_ORDERER.CreateDate.url}" {if $MY_PROJECTS_ORDERER.CreateDate.order eq true}class="sort"{/if}>Дата создания</a></th>
						</tr>
					</thead> 
					<tbody>
				{foreach name=myProjects from=$MY_PROJECTS item=element} {* Выводит мои проекты*}
						<tr class="{if $smarty.foreach.myProjects.index % 2 == 0}odd{else}even{/if}">
							<td><input name="delId" type="checkbox" /></td>
							<td><a href="/my/project/show/{$element.ProjectID}/">{$element.Name}</a><br />
							</td>
							<td>{$element.Description}</td>
							<td class="new">{$element.NEW}</td><td class="confirmed">{$element.IDENTIFIED}</td><td class="assigned">{$element.ASSESSED}</td><td class="solved">{$element.RESOLVED}</td><td class="closed">{$element.CLOSED}</td>
							<td><strong {if $element.CountRequests neq 0}class="strongest"{/if}>{$element.CountRequests}</strong></td>
							<td>{$element.CountUsers}</td>
							<td>{$element.CreateDate}</td>
						</tr>
				{/foreach}
					</tbody>
			  	</table>
				<div class="groupier">
					<input value="Удалить" name="delBtn" type="button" />
				</div>
			</form>
			{else}
				<span>Проектов нет</span>
			{/if}
		</div>
		<div id="all_projects">
			<div class="groupier">
				{$MEMBER_PROJECTS_PAGINATOR}
			</div>
			{if $PROJECTS_WITHOUT_ME neq NULL}
			 <table class="projects_table">
				<thead> 
					<tr>
						<th><a href="{$MEMBER_PROJECTS_ORDERER.Name.url}#all_projects" {if $MEMBER_PROJECTS_ORDERER.Name.order eq true}class="sort"{/if}">Проект</a></th>
						<th><a href="{$MEMBER_PROJECTS_ORDERER.Description.url}#all_projects" {if $MEMBER_PROJECTS_ORDERER.Description.order eq true}class="sort"{/if}">Заголовок</a></th>
						<th><a href="{$MEMBER_PROJECTS_ORDERER.NickName.url}#all_projects" {if $MEMBER_PROJECTS_ORDERER.NickName.order eq true}class="sort"{/if}">Владелец</a></th>
						<th colspan="5">Отчётов</th>
						<th><a href="{$MEMBER_PROJECTS_ORDERER.CountRequests.url}#all_projects" {if $MEMBER_PROJECTS_ORDERER.CountRequests.order eq true}class="sort"{/if}">Заявки</a></th>
						<th><a href="{$MEMBER_PROJECTS_ORDERER.CreateDate.url}#all_projects" {if $MEMBER_PROJECTS_ORDERER.CreateDate.order eq true}class="sort"{/if}">Дата</a></th>
					</tr>
				</thead> 
				<tbody>
				{foreach name=notMyProjects from=$PROJECTS_WITHOUT_ME item=element} {* Выводит мои проекты*}
				  <tr class="{if $smarty.foreach.notMyProjects.index % 2 == 0}odd{else}even{/if}">
					<td><a href="/my/project/show/{$element.ProjectID}/">{$element.Name}</a></td>
					<td>{$element.Description}</td>
					<td><a href="#">Sudo777</a></td>
					<td class="new">{$element.NEW}</td><td class="confirmed">{$element.IDENTIFIED}</td><td class="assigned">{$element.ASSESSED}</td><td class="solved">{$element.RESOLVED}</td><td class="closed">{$element.CLOSED}</td>
					<td><strong {if $element.CountRequests neq 0}class="strongest"{/if}>{$element.CountRequests}</strong></td>
					<td>{$element.CreateDate}</td>
				  </tr>
				{/foreach}
				</tbody>
			</table>
			{else}
				<span>Проектов нет</span>
			{/if}
			</div>
		</div>
	</div>

{/block}