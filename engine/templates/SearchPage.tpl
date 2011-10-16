{extends file="info.base.tpl"}
{block name=body}
<div id="content_body">
	<form action="/search/result/" method="get">
		<div class="add_form fixed_width">		
			<div id="hdr">Поиск</div>
			<dl>
			<dt>По названию:</dt>
			<dd><input type="text" name="by_proj" value="" /></dd>
			<dd class="subm"><input type="submit" value="Найти" /></dd>
			</dl>
		</div>
	</form>
</div>
{/block}