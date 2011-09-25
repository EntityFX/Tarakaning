{extends file="info.base.tpl"}
{block name=body}
	<div id="content_body">

		<form action="" method="post">
			<div class="add_form">
				<div id="hdr">Добавление отчёта об ошибке</div>
				{if $ERROR neq ""}<strong class="error" id="error">{$ERROR}</strong>{/if}
				<dl>
					<dt><label for="project_id">Выберите проект</label></dt>
					<dd>
						<select id="project_id" name="project_id">
							{if $PROJECTS_LIST neq NULL}
							{foreach from=$PROJECTS_LIST item=element} {* Выводит все проекты мои и не только*}
							<option value="{$element.ProjectID}">{$element.Name}</option>
							{/foreach}
							{/if}
						</select>
					</dd>
					<dt><label for="title">Укажите заголовок</label></dt>
					<dd><input type="text" id="title" name="title" value="{$DATA.title}" /></dd>
					<dt><label for="item_type">Вид</label></dt>
					<dd>									
						<select id="item_type" name="item_type" style="font-weight: bold;">
							<option value="Task">Задача</option>
							<option value="Defect">Дефект</option>
						</select>
					</dd>
					<dt><label for="error_type">Тип ошибки</label></dt>
					<dd>									
						<select id="error_type" name="error_type">
							<option value="Crash">Крах</option>
							<option value="Cosmetic">Косметическая</option>
							<option value="Exception">Исключение</option>
							<option value="Functional">Функциональная</option>
							<option value="Minor">Незначительная</option>
							<option selected="selected" value="Major">Важная</option>
							<option value="Install">Ошибка установки</option>
							<option value="Block">Блокирующая</option>
						</select>
					</dd>
					<dt><label for="priority">Приоритет</label></dt>
					<dd>									
						<select id="priority" name="priority">
							<option value="0">Низкий</option>
							<option value="1" selected="selected">Обычный</option>
							<option value="2">Важный</option>
						</select>
					</dd>
					<dt><label for="asigned_to">Назначено</label></dt>
					<dd>									
						<select id="asigned_to" name="asigned_to">
							<option value="0"></option>
						</select>
					</dd>
					<dt><label for="description">Описание</label></dt>
					<dd><textarea id="description" name="description" rows="7" cols="20" >{$DATA.description}</textarea></dd>
					<dt><label for="steps">Действия, которые привели к ошибке</label></dt>
					<dd><textarea id="steps" name="steps" rows="10" cols="20" >{$DATA.steps}</textarea></dd>
					<dt>&nbsp;</dt>
					<dd class="subm"><input type="submit" name="add_report" value="Создать отчёт" /></dd>						
				</dl>
			</div>
		</form>
	</div>
{/block}