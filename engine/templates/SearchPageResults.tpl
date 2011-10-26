{extends file="info.base.tpl"}
{block name=body}

<div id="content_body">
	<form action="" method="get">
		<div class="add_form fixed_width">		
			<div id="hdr">Поиск</div>
			<dl>
			<dt>По названию:</dt>
			<dd><input type="text" name="by_proj" value="{$SEARCH_QUERY}" /></dd>
			<dd class="subm"><input type="submit" value="Найти" /></dd>
			</dl>
		</div>
	</form>
	{$MY_BUGS_PAGINATOR}
	{$ERROR}
	{if $SEARCH_QUERY neq ''}
		{if $AR_SEARCH_ITEM neq NULL}
			{if $COUNT neq NULL}Количество результатов поиска: {$COUNT}{/if}
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
			По запросу "<strong>{$SEARCH_QUERY}</strong>" ничего не найдено. :( Но не время грустить! Пора работать!
		{/if}
	{/if}
</div>
{/block}