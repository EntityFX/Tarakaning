{extends file="info.base.tpl"}
{block name=body}
<div id="content_body">
	<div class="add_form fixed_width">		
		<div id="hdr">���������� � ������������</div>		
		<dl>
			<dt>���:</dt>
			<dd>{if $AR_USER_INFO.Name eq ""}&nbsp;{else}{$AR_USER_INFO.Name}{/if}</dd>
			<dt>�������:</dt>
			<dd>{if $AR_USER_INFO.Surname eq ""}&nbsp;{else}{$AR_USER_INFO.Surname}{/if}</dd>			
			<dt>��������:</dt>
			<dd>{if $AR_USER_INFO.SecondName eq ""}&nbsp;{else}{$AR_USER_INFO.SecondName}{/if}</dd>
			<dt>���:</dt>
			<dd>{if $AR_USER_INFO.NickName eq ""}&nbsp;{else}{$AR_USER_INFO.NickName}{/if}</dd>			
			{if $B_IS_ME eq true}<dd class="subm">
				<form action="/profile/edit/" method="post">
				<div><input type="submit" value="�������������" /></div>
				</form>
			</dd>{/if}
		</dl>
		
	</div>
	
</div>
{/block}