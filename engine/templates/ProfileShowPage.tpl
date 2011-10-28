{extends file="info.base.tpl"}
{block name=body}
<div id="content_body">
	<div class="add_form fixed_width">		
		<div id="hdr">Информация о пользователе</div>		
		<dl>
			<dt>Логин:</dt>
			<dd>{if $AR_USER_INFO.NickName eq ""}&nbsp;{else}{$AR_USER_INFO.NickName}{/if}</dd>
			<dt>Имя:</dt>
			<dd>{if $AR_USER_INFO.Name eq ""}&nbsp;{else}{$AR_USER_INFO.Name}{/if}</dd>
			<dt>Фамилия:</dt>
			<dd>{if $AR_USER_INFO.Surname eq ""}&nbsp;{else}{$AR_USER_INFO.Surname}{/if}</dd>			
			<dt>Отчество:</dt>
			<dd>{if $AR_USER_INFO.SecondName eq ""}&nbsp;{else}{$AR_USER_INFO.SecondName}{/if}</dd>
			<dt>e-mail:</dt>
			<dd>{if $AR_USER_INFO.Email eq ""}&nbsp;{else}<a href="mailto:{$AR_USER_INFO.Email}">{$AR_USER_INFO.Email}</a>{/if}</dd>	
			{if $B_IS_ME eq true}<dd class="subm">
				<form action="/profile/edit/" method="post">
				<div><input type="submit" value="Редактировать" /></div>
				</form>
			</dd>{/if}
		</dl>
		
	</div>
	
</div>
{/block}