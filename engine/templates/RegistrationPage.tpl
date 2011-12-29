{extends file="MainBasePage.base.tpl"}
{block name=script}
		<script type="text/javascript">
		/* <![CDATA[ */
			$(document).ready(function() {
				$("#tabs").tabs();
				$("input:button, input:submit, a").button();
			});
		/* ]]>*/
		</script>
{/block}
{block name=body}
<div id="content_body">
	<form action="/registration/do/" method="post">
		<div class="add_form" id="reg">
			<div id="hdr">�����������</div>
			{if $ERROR neq ""}<strong class="error" id="error">{$ERROR}</strong>
			{/if}
			<dl>
				<dt><label for="login">����� <strong>*</strong></label></dt>
				<dd><input type="text" name="login" id="login" value="{$DATA.login}"/><span>������ ������ - ��������� �����, ��������� - ��������� �����, �����, ".", "-", "_" � "@"</span></dd>
				<dt><label for="password">������ <strong>*</strong></label></dt>
				<dd><input type="password" name="password" id="password"/><span>����������� ����� ������ - 7 ��������</span></dd>
				<dt><label for="commitPass">��������� ������ <strong>*</strong></label></dt>
				<dd><input type="password" name="commitPass" id="commitPass"/></dd>	
				<dt><label for="captcha">CAPTCHA <strong>*</strong></label></dt>
				<dd><input type="text" name="captcha" id="captcha"/></dd>	
				<dt>&nbsp;</dt>
				<dd>{$CAPTCHA}</dd>	
			</dl>
			<input type="hidden" name="captchaId" id="captchaId" value="{$CAPTCHA_ID}" />
			<span>&nbsp;<strong>*</strong> ����, ������������ ��� ����������</span>
			<hr />
			<dl>
				<dt><label for="name">���</label></dt>
				<dd><input type="text" name="name" id="name" value="{$DATA.name}"/></dd>
				<dt><label for="surname">�������</label></dt>
				<dd><input type="text" name="surname" id="surname" value="{$DATA.surname}"/></dd>
				<dt><label for="secondName">��������</label></dt>
				<dd><input type="text" name="secondName" id="secondName" value="{$DATA.secondName}"/></dd>
				<dt><label for="eMail">E-mail</label></dt>
				<dd><input type="text" name="eMail" id="eMail" value="{$DATA.eMail}"/></dd>				
				<dt>&nbsp;</dt>
				<dd class="subm"><input type="submit" value="������������������" /></dd>						
			</dl>
		</div>
	</form>
</div>
{/block}