{extends file="main.base.tpl"}

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

				{$smarty.block.child}
			});
		/* ]]>*/
		</script>
{/block}
{block name=info}

<div id="goTask">
	<div class="panel">
		<form action="#">
			<div>
				<a href="/bug/add/" alt="+" title="Добавить новую задачу">З</a>
				<a href="/my/project/new/" alt="+" title="Добавить новый проект">П</a>
				<label>№ </label>
				<input type="text" maxlength="10" name="item" id="item" />
				<input type="submit" value="OK" id="submit_item" />
			</div>
		</form>
	</div>
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