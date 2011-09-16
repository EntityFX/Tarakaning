{extends file="info.base.tpl"}
{block name=body}
<div id="content_body">
	
	{if $GOOD eq TRUE}
	<div class="operationResultBox">
		<strong class="ok" id="good">��������� ���������</strong>
	</div> 
	{/if}
	{if $ERROR neq ""}
	<div class="operationResultBox">
		<strong class="error" id="error">{$ERROR}</strong>
	</div> 
	{/if}
	
	<div id="tabs">
			<ul>
				<li><a href="#edit_info"><span>������������� ����������</span></a></li>
				<li><a href="#pass_change"><span>����� ������</span></a></li>			
			</ul>
			<div id="edit_info" >
				<form action="/profile/edit/" method="post">
					<div class="info_div">
						<dl class="prof">
							<dt>���:</dt>
							<dd><input type="text" name="Name" value="{$AR_USER_INFO.Name}" /></dd>
							<dt>�������:</dt>
							<dd><input type="text" name="Surname" value="{$AR_USER_INFO.Surname}" /></dd>
							<dt>��������:</dt>
							<dd><input type="text" name="SecondName" value="{$AR_USER_INFO.SecondName}" /></dd>
							{*<dt>���:</dt>
							<dd><input type="text" name="NickName" value="{$AR_USER_INFO.NickName}" /></dd>*}
							<dt>e-mail:</dt>
							<dd><input type="text" name="Email" value="{$AR_USER_INFO.Email}" /></dd>
							<dd class="subm"><input type="submit" name="save_btn" value="���������" /></dd>
						</dl>
					</div>
				</form>
			</div>
			<div id="pass_change" >
				<form action="/profile/edit/newpass/" method="post">
					<div class="info_div">
						<dl class="prof">
							<dt><label for="oldPassword">������ ������:</label></dt>
							<dd><input type="password" name="oldPassword"  id="oldPassword" /></dd>
							<dt><label for="newPassword">����� ������:</label></dt>
							<dd><input type="password" name="newPassword" id="newPassword" /></dd>
							<dt><label for="newPasswordRepeat">������ ������ ������:</label></dt>
							<dd><input type="password" name="newPasswordRepeat" id="newPasswordRepeat" /></dd>
							<dd class="subm"><input type="submit" name="save_btn" value="������� ������" /></dd>
						</dl>
					</div>
				</form>
			</div>
	</div>	
</div>
{/block}