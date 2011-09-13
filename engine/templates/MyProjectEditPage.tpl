{extends file="info.base.tpl"}
{block name=body}
<div id="content_body"> 
	<form action="" method="post"> 
		<div class="add_form"> 
			<div id="hdr">Редактирование проекта</div> 
			{if $GOOD eq TRUE}
				<strong class="ok" id="good">Проект успешно создан</strong>
			{/if}
			{if $ERROR neq ""}<strong class="error" id="error">{$ERROR}</strong>
			{/if}
			<a href="my_project_properties.html"> 
					Просмотреть проект			
			</a> 
			<dl> 
				<dt><label for="project_name">Имя проекта</label></dt> 
				<dd><input type="text" name="project_name" id="project_name" value="{$PROJECT_DATA.Name}" /></dd> 
				<dt><label for="description">Описание проекта</label></dt> 
				<dd><textarea name="description" rows="10" cols="5" id="description">{$PROJECT_DATA.Description}</textarea></dd> 
				<dt>&nbsp;</dt> 
				<dd class="subm"><input type="submit" value="Сохранить изменения" name="save" /></dd>			
			</dl> 
			<input type="hidden" name="project_id" value="{$PROJECT_DATA.ProjectID}" />	
		</div> 
	</form> 
</div>
{/block}