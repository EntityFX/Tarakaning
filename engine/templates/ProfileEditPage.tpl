{extends file="info.base.tpl"}
{block name=body}
<div id="content_body">
	<div id="tabs">
			<ul>
				<li><a href="#edit_info"><span>Редактировать информацию</span></a></li>
				<li><a href="#pass_change"><span>Смена пароля</span></a></li>			
			</ul>
			<div id="edit_info" >
				<form action="/profile/edit/">
					<div class="info_div">
						<dl class="prof">
							<dt>Имя:</dt>
							<dd><input type="text" name="u_name" value="{$AR_USER_INFO.Name}" /></dd>
							<dt>Фамилия:</dt>
							<dd><input type="text" name="u_surname" value="{$AR_USER_INFO.Surname}" /></dd>
							<dt>Отчество:</dt>
							<dd><input type="text" name="u_second_name" value="{$AR_USER_INFO.SecondName}" /></dd>
							<dt>Ник:</dt>
							<dd><input type="text" name="u_nick_name" value="{$AR_USER_INFO.NickName}" /></dd>
							<dt>e-mail:</dt>
							<dd><input type="text" name="u_e_mail" value="{$AR_USER_INFO.Email}" /></dd>
							<dd class="subm"><input type="submit" name="save_btn" value="Сохранить" /></dd>
						</dl>
					</div>
				</form>
			</div>
			<div id="pass_change" >
				<form action="#" method="post">
					<div class="info_div">
						<dl class="prof">
							<dt><label for="old_pass">Старый пароль:</label></dt>
							<dd><input type="password" name="old_pass"  id="old_pass" /></dd>
							<dt><label for="new_pass">Новый пароль:</label></dt>
							<dd><input type="password" name="new_pass" id="new_pass" /></dd>
							<dt><label for="new_repass">Повтор нового пароля:</label></dt>
							<dd><input type="password" name="new_repass" id="new_repass" /></dd>
							<dd class="subm"><input type="submit" name="save_btn" value="Сменить пароль" /></dd>
						</dl>
					</div>
				</form>
			</div>
	</div>	
</div>
{/block}