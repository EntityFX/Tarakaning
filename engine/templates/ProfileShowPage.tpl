{extends file="InfoBasePage.base.tpl"}
{block name=body}
<div id="content_body">
	<div class="add_form fixed_width">		
		<div id="hdr">Информация о пользователе</div>		
		<dl>
			<dt>Логин:</dt>
			<dd>{if $AR_USER_INFO.NICK eq ""}&nbsp;{else}{$AR_USER_INFO.NICK}{/if}</dd>
			<dt>Имя:</dt>
			<dd>{if $AR_USER_INFO.FRST_NM eq ""}&nbsp;{else}{$AR_USER_INFO.FRST_NM}{/if}</dd>
			<dt>Фамилия:</dt>
			<dd>{if $AR_USER_INFO.LAST_NM eq ""}&nbsp;{else}{$AR_USER_INFO.LAST_NM}{/if}</dd>			
			<dt>Отчество:</dt>
			<dd>{if $AR_USER_INFO.SECND_NM eq ""}&nbsp;{else}{$AR_USER_INFO.SECND_NM}{/if}</dd>
			<dt>e-mail:</dt>
			<dd>{if $AR_USER_INFO.EMAIL eq ""}&nbsp;{else}<a href="mailto:{$AR_USER_INFO.Email}">{$AR_USER_INFO.EMAIL}</a>{/if}</dd>	
			{if $B_IS_ME eq true}<dd class="subm">
				<form action="/profile/edit/" method="post">
				<div><input type="submit" value="Редактировать" /></div>
				</form>
			</dd>{/if}
		</dl>
		
	</div>
	
</div>
{/block}