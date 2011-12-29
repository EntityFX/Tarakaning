{extends file="MainBasePage.base.tpl"}

{block name=script}
		<script type="text/javascript">
		/* <![CDATA[ */
			$(document).ready(function() {
				$("#tabs").tabs();
				$("input:button, input:submit, button, .groupier a, .groupier li span, #exit, #goTask div.panel a").button();	
				


				$("#submit_item").click(function(){
					location.replace("/bug/show/"+$("#item").val()+"/");
					return false;
				});
				
				{if $PROJECT_SELECTION_FLAG eq true}
					$("#project_id").change(function(){
						$("#selectProjectForm").submit();
					});
				{/if}
				{$smarty.block.child}
			});
		/* ]]>*/
		</script>
{/block}
{block name=info}

<div id="goTask">
	<div class="panel">
		<form action="#" id="selectProjectForm">
			<div>
				<label for="project_id">Выберите проект</label>
				<select id="project_id" name="project_id" {if $PROJECT_SELECTION_FLAG neq true}disabled="disabled"{/if}>
				{if $PROJECTS.PROJECTS_LIST neq NULL}
					{html_options options=$PROJECTS.PROJECTS_LIST selected=$PROJECTS.selected}
				{/if}
				</select>
			</div>
		</form>
		<form action="#">
			<div>
				<span class="delmiter"></span>
				{if $PROJECTS.PROJECTS_LIST neq NULL}
				<a href="/bug/add/" title="Добавить новую задачу">Новая задача</a>
				{/if}
				<a href="/my/project/new/" title="Добавить новый проект">Новый проект</a>
				<span class="delmiter"></span>
				<label for="item">Переход к № </label>
				<input type="text" maxlength="10" name="item" id="item" />
				<input type="submit" value="OK" id="submit_item" />
			</div>
		</form>
	</div>
</div>
<div id="account_panel">
	<strong>{$LOGIN}</strong>
	<a href="/profile/edit/" title="Настройки профиля"><img src="/images/settings.png" alt="настройки" /></a><br/>
	{*<span>{$FULLNAME}</span><br />*}
	<span id="enter_time">Вошёл: <span style="color: #aaa">{$TIME}</span></span><br />
	<a href="/logout/" id="exit" >Выход</a>
</div>
{/block}
{block name=menu}
<div id="main_menu">
	<ul>
		{foreach name=bugComments from=$MAIN_MENU item=element} {* Комментарии отчёта *}
			<li {if $element.cur eq true}id="cur"{/if}>
				{if $element.cur eq true}
				{$element.title}
				{else}
				<a href="{$element.url}">{$element.title}</a>
				{/if}
			</li>
		{/foreach}
	</ul>
</div>
{/block}