{extends file="info.base.tpl"}
{block name=body}

<div id="content_body">
	<form action="" method="get">
		<div class="add_form fixed_width">		
			<div id="hdr">Поиск</div>
			<dl>
			<dt>По названию:</dt>
			<dd><input type="text" name="by_proj" value="{$SEARCH_QUERY}" /></dd>
			<dd class="subm"><input type="submit" value="Найти" name="search" /></dd>
			</dl>
		</div>
	</form>
	{$ERROR}
	{if $SEARCH_QUERY neq ''}
		{if $AR_SEARCH_ITEM neq NULL}
			<div class="groupier">
				{if $COUNT neq NULL}Количество результатов поиска: {$COUNT}{/if}
				{$PROJECT_SEARCH_PAGINATOR}
			</div>
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
				{if $element.ProjectRelation eq 0}
				<form action="#" method="post">
					<div>
						<input type="hidden" value="{$element.ProjectID}" name="projectID" />
						<input type="submit" value="Подать заявку" class="standard" />
					</div>		
				</form>
				{elseif $element.ProjectRelation eq 1}
				<form action="#" method="post">
					<div>
						<input type="submit" value="Прервать участие" class="red" />
					</div>		
				</form>
				{else}
				<span class="green">Вы владелец</span>
				{/if}
			</div>
			{/foreach}
		{else}
			По запросу "<strong>{$SEARCH_QUERY}</strong>" ничего не найдено.
		{/if}
	{/if}
</div>
{/block}