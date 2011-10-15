{extends file="info.base.tpl"}
{block name=body}

<div id="content_body">
	<form action="/search/result/" method="get">
		<div class="add_form fixed_width">		
			<div id="hdr">Поиск</div>
			<dl>
			<dt>По названию:</dt>
			<dd><input type="text" name="by_proj" value="{$AR_SEARCH_FIELD_by_proj}" /></dd>
			
			<dt>По автору:</dt>
			<dd><input type="text" name="by_author" value="" /></dd>
			<dd class="subm"><input type="submit" value="Найти" /></dd>
			</dl>
		</div>
	</form>
	{$MY_BUGS_PAGINATOR}
	{$ERROR}
	{if $STATUS neq NULL}Количество результатов поиска: {$STATUS}{/if}
	{if $AR_SEARCH_ITEM neq NULL}
	{foreach name=searchResult from=$AR_SEARCH_ITEM item=element}
	<div class="find_results">
		<div>
		<strong>{$element.Name}</strong>
		<span class="author">автор:</span>
		<span><a href="/profile/show/{$element.OwnerID}/">{$element.NickName}</a></span>
		</div>
		<p>
			{$element.Description}
		</p>
		<form action="#" method="post">
			<div>
				<input type="submit" value="Принять участие" />
			</div>		
		</form>
	</div>
	{/foreach}
	{else}
		По запросу "<strong>{$AR_SEARCH_FIELD_by_proj}</strong>" ничего не найдено. :( Но не время грустить! Пора работать!
	{/if}
</div>
{/block}