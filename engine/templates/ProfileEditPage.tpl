{extends file="info.base.tpl"}
{block name=body}
<div id="content_body">
	
	{if $GOOD eq TRUE}
	<div class="messageBox goodBox">
		<strong class="ok" id="good">Изменения сохранены</strong>
	</div> 
	{/if}
	{if $ERROR neq ""}
	<div class="messageBox errorBox">
		<strong class="error" id="error">{$ERROR}</strong>
	</div> 
	{/if}
	
	<div id="tabs">
			<ul>
				<li><a href="#edit_info"><span>Редактировать информацию</span></a></li>
				<li><a href="#pass_change"><span>Смена пароля</span></a></li>
				<li><a href="#settings"><span>Дополнительные настройки</span></a></li>			
			</ul>
			<div id="edit_info" >
				<form action="/profile/edit/" method="post">
					<div class="info_div">
						<dl class="prof">
							<dt>Имя:</dt>
							<dd><input type="text" name="Name" value="{$AR_USER_INFO.Name}" /></dd>
							<dt>Фамилия:</dt>
							<dd><input type="text" name="Surname" value="{$AR_USER_INFO.Surname}" /></dd>
							<dt>Отчество:</dt>
							<dd><input type="text" name="SecondName" value="{$AR_USER_INFO.SecondName}" /></dd>
							<dt>e-mail:</dt>
							<dd><input type="text" name="Email" value="{$AR_USER_INFO.Email}" /></dd>
							<dd class="subm"><input type="submit" name="save_profile" value="Сохранить" /></dd>
						</dl>
					</div>
				</form>
			</div>
			<div id="pass_change" >
				<form action="/profile/edit/newpass/" method="post">
					<div class="info_div">
						<dl class="prof">
							<dt><label for="oldPassword">Старый пароль:</label></dt>
							<dd><input type="password" name="oldPassword"  id="oldPassword" /></dd>
							<dt><label for="newPassword">Новый пароль:</label></dt>
							<dd><input type="password" name="newPassword" id="newPassword" /></dd>
							<dt><label for="newPasswordRepeat">Повтор нового пароля:</label></dt>
							<dd><input type="password" name="newPasswordRepeat" id="newPasswordRepeat" /></dd>
							<dd class="subm"><input type="submit" name="save_btn" value="Сменить пароль" /></dd>
						</dl>
					</div>
				</form>
			</div>
			<div id="settings" >
				<form action="/profile/edit/" method="post">
					<div class="info_div">
						<dl class="prof">
							<dt><label for="defaultProject">Проект по-умолчанию:</label></dt>
							<dd>				
								<select id="defaultProject" name="defaultProject" {if $PROJECTS.PROJECTS_LIST eq NULL}disabled="disabled"{/if}>
								{if $PROJECTS.PROJECTS_LIST neq NULL}
									{html_options options=$PROJECTS.PROJECTS_LIST selected=$PROJECTS.selected}
								{/if}
								</select>
							</dd>
							<dd class="subm"><input type="submit" name="save_project" value="Сохранить" {if $PROJECTS.PROJECTS_LIST eq NULL}disabled="disabled"{/if}/></dd>
						</dl>
					</div>
				</form>
			</div>
	</div>	
</div>
{/block}