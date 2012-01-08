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
	<div id="enter">
		<div id="hdr">Вход в систему</div>
		<form action="/login/do/" method="post" id="form">
			<div>
			{if $ERROR neq ""}
				<strong class="error" id="error">{$ERROR}</strong>
			{else if $GOOD eq TRUE}
				<strong class="ok" id="good">Пользователь зарегистрирован</strong>
			{/if}
				<dl>
					<dt><label for="login">Логин </label></dt><dd><input id="login" class="edit" type="text" name="login" /></dd>
					<dt><label for="psw">Пароль </label></dt><dd><input id="psw" class="edit" type="password" name="pswrd" /></dd>
					<dt style="margin-top: 1px;"><a href="/registration/">Регистрация</a></dt><dd id="button"><input type="submit" name="sigIn" id="sigIn" value="Войти" class="button" /></dd>
				</dl>
			</div>
		</form>
	</div>
{/block}