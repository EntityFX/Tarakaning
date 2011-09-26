{extends file="info.base.tpl"}
{block name=body}
		{* define the function *}
		{function name=bug_type}
		    {if $value eq NEW}new{else if $value eq IDENTIFIED}confirmed{else if $value eq ASSESSED}assigned{else if $value eq RESOLVED}solved{else if $value eq CLOSED}closed{/if}
		{/function}
<div id="content_body">
	{if $PROJECTS_LIST neq NULL}
		<div class="groupier">
			
			<form action="#" id="selectProjectForm">
				<div>
				<label for="project_id">Проект</label>
				<select id="project_id" name="project_id">
					{if $PROJECTS_LIST neq NULL}
					{foreach from=$PROJECTS_LIST item=element} {* Выводит все проекты мои и не только*}
					<option value="{$element.ProjectID}">{$element.Name}</option>
					{/foreach}
					{/if}
				</select>
				</div>
			</form>
			<form action="/bug/add/" method="post"><div>
				<input type="submit" value="Добавить отчёт" title="Добавить новый отчёт об ошибке" name="add"/>
			</div></form>
			{$PROJECT_BUGS_PAGINATOR}
		</div>

	
			<div>
				{if $MY_BUGS neq NULL}
				<form action="#" class="reports_form">
					<!--<a class="z" href="#">Выбрать всё</a>-->
					<table class="report_table">
						<thead>
							<tr>
								<th><input name="del_all" type="checkbox" title=""/></th>
								<th><a href="{$MY_BUGS_ORDERER.ID.url}" {if $MY_BUGS_ORDERER.ID.order eq true}class="sort"{/if}>№</a></th>
								<th><a href="{$MY_BUGS_ORDERER.Status.url}" {if $MY_BUGS_ORDERER.Status.order eq true}class="sort"{/if}>Статус</a></th>
								<th><a href="{$MY_BUGS_ORDERER.Title.url}" {if $MY_BUGS_ORDERER.Title.order eq true}class="sort"{/if}>Заголовок</a></th>
								<th><a href="{$MY_BUGS_ORDERER.PriorityLevel.url}" {if $MY_BUGS_ORDERER.PriorityLevel.order eq true}class="sort"{/if}>Приоритет</a></th>
								<th><a href="{$MY_BUGS_ORDERER.NickName.url}" {if $MY_BUGS_ORDERER.NickName.order eq true}class="sort"{/if}>Владелец</a></th>
								<th><a href="{$MY_BUGS_ORDERER.AssignedNickName.url}" {if $MY_BUGS_ORDERER.AssignedNickName.order eq true}class="sort"{/if}>Назначено</a></th>
								<th><a href="{$MY_BUGS_ORDERER.ErrorType.url}" {if $MY_BUGS_ORDERER.ErrorType.order eq true}class="sort"{/if}>Тип</a></th>
								<th><a href="{$MY_BUGS_ORDERER.Time.url}" {if $MY_BUGS_ORDERER.Time.order eq true}class="sort"{/if}>Дата</a></th>
							</tr>
						</thead>
						<tbody>
						{foreach name=myBugs from=$MY_BUGS item=element} {* Выводит мои проекты*}
							<tr class="{bug_type value=$element.Status}">
							    <td><input name="del1" type="checkbox" /></td>
								<td><a href="/bug/show/{$element.ID}/" class="sort">{$element.ID}</a></td>
								<td>{$element.StatusN}</td>
								<td>{$element.Title}</td>
								<td>{$element.PriorityLevel}</td>
								<td><a href="/profile/show/{$element.UserID}/">{$element.NickName}</a></td>
								<td><a href="/profile/show/{$element.AssignedTo}/">{$element.AssignedNickName}</a></td>
								<td>{$element.ErrorType}</td>
								<td>{$element.Time}</td>
							</tr>
						{/foreach}
						</tbody>
					</table>
					<div class="groupier">
						<input type="submit" value="Удалить выделенные" title="Удалить выделенные" name="del" />
					</div>
				</form>
				{else}
					<strong>Багов нет</strong>
				{/if}
			</div>
	{else}
		<strong>У вас нет проектов. Создайте или подпишитесь на проект.</strong>
	{/if}
</div>
{/block}