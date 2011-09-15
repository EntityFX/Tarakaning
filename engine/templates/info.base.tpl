{extends file="main.base.tpl"}

{block name=script}
		<script type="text/javascript">
		/* <![CDATA[ */
			$(document).ready(function() {
				$("#tabs").tabs();
				$("input:button, input:submit, button, .groupier a, .groupier li span, #exit").button();	
				$('.reports_form').checkboxes({
					onText: "*",
					offText: "",
					titleOn: "Отметить все",
					titleOff: "Убрать всё"
				});
				
				$("#project_id").change(function(){
					var form=$("#selectProjectForm");
					form[0].submit();
				});
			});
		/* ]]>*/
		</script>
{/block}
{block name=info}
<div id="goTask">
	<form action="#">
		<div>
			<label>№ </label><input type="text" maxlength="10" name="bug" />

			<input type="submit" value="OK" />
		</div>
	</form>
</div>
<div id="account_panel">
	<strong>{$LOGIN}</strong><br/>
	<span>{$FULLNAME}</span><br />
	<span>Вошёл: <span style="color: #aaa">{$TIME}</span></span><br />
	<a href="/logout/" id="exit" >Выход</a>
</div>
{/block}
{block name=menu}
<div id="main_menu">
	<ul>
		<li id="cur"><a href="/my/bugs/">Мои отчёты</a></li>
		<li><a href="/my/project/bugs/">Отчёты проекта</a></li>
		<li><a href="/my/projects/">Мои проекты</a></li>
		<li><a href="subscribes.html">Мои заявки</a></li>
		<li><a href="find.html">Поиск</a></li>
		<li><a href="/profile/show/">Профиль</a></li>
	</ul>
</div>
{/block}