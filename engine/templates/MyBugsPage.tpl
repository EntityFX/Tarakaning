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
				<label for="item_kind">Показать </label> 
				<select id="item_kind" name="item_kind">
					<option value="*">Дефекты и задачи</option>
					<option value="1">Дефекты</option>
					<option value="2">Задачи</option>
				</select>
				</div>
			</form>
			<form action="/bug/add/" method="post"><div>
				<input type="submit" value="Добавить дефект" title="Добавить" name="add_defect"/>
			</div></form>
			{$MY_BUGS_PAGINATOR}
		</div>

	
			<div>
				{if $MY_BUGS neq NULL}
				<form action="#" class="reports_form">
					<!--<a class="z" href="#">Выбрать всё</a>-->
					<table class="report_table">
						<thead>
							<tr>
								<th><input name="del_all" type="button" value="" title="" style="width:18px; padding: 0px; height: 18px;" /></th>
								<th><a href="{$MY_BUGS_ORDERER.ID.url}" class="sort">№ &uarr;</a></th>
								<th><a href="{$MY_BUGS_ORDERER.Status.url}">Статус</a></th>
								<th><a href="{$MY_BUGS_ORDERER.Title.url}">Заголовок</a></th>
								<th><a href="{$MY_BUGS_ORDERER.AssignedNickName.url}">Назначена</a></th>
								<th><a href="{$MY_BUGS_ORDERER.PriorityLevel.url}">Приоритет</a></th>
								<th><a href="{$MY_BUGS_ORDERER.ErrorType.url}">Тип</a></th>
								<th><a href="{$MY_BUGS_ORDERER.Time.url}">Дата</a></th>

							</tr>
						</thead>
						<tbody>
						{foreach name=myBugs from=$MY_BUGS item=element} {* Выводит мои проекты*}
							<tr class="{bug_type value=$element.Status}">
							    <td><input name="del1" type="checkbox" /></td>
								<td><a href="/bug/show/{$element.ID}/" class="sort">{$element.ID}</a></td>
								<td>{$element.StatusN}</td>
								<td>{$element.Title}</td>
								<td>{if $element.AssignedTo neq null}<a href="/profile/show/{$element.AssignedTo}/">{$element.AssignedNickName}</a>{/if}</td>
								<td>{$element.PriorityLevel}</td>
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

