{extends file="info.base.tpl"}
{block name=body}

<div id="content_body">
	<form action="" method="get">
		<div class="add_form fixed_width">		
			<div id="hdr">�����</div>
			<dl>
			<dt>�� ��������:</dt>
			<dd><input type="text" name="by_proj" value="{$SEARCH_QUERY}" /></dd>
			<dd class="subm"><input type="submit" value="�����" /></dd>
			</dl>
		</div>
	</form>
	{$MY_BUGS_PAGINATOR}
	{$ERROR}
	{if $SEARCH_QUERY neq ''}
		{if $AR_SEARCH_ITEM neq NULL}
			{if $COUNT neq NULL}���������� ����������� ������: {$COUNT}{/if}
			{foreach name=searchResult from=$AR_SEARCH_ITEM item=element}
			<div class="find_results">
				<div>
				<strong>{$element.Name}</strong>
				<span class="author">�����:</span>
				<span><a href="/profile/show/{$element.OwnerID}/">{$element.NickName}</a></span>
				</div>
				<p>
					{$element.Description}
				</p>
				<form action="#" method="post">
					<div>
						<input type="submit" value="������� �������" />
					</div>		
				</form>
			</div>
			{/foreach}
		{else}
			�� ������� "<strong>{$SEARCH_QUERY}</strong>" ������ �� �������. :( �� �� ����� ��������! ���� ��������!
		{/if}
	{/if}
</div>
{/block}