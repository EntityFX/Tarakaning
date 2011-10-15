{extends file="info.base.tpl"}
{block name=body}

<div id="content_body">
	<form action="/search/result/" method="get">
		<div class="add_form fixed_width">		
			<div id="hdr">�����</div>
			<dl>
			<dt>�� ��������:</dt>
			<dd><input type="text" name="by_proj" value="{$AR_SEARCH_FIELD_by_proj}" /></dd>
			
			<dt>�� ������:</dt>
			<dd><input type="text" name="by_author" value="" /></dd>
			<dd class="subm"><input type="submit" value="�����" /></dd>
			</dl>
		</div>
	</form>
	{$MY_BUGS_PAGINATOR}
	{$ERROR}
	{if $STATUS neq NULL}���������� ����������� ������: {$STATUS}{/if}
	{if $AR_SEARCH_ITEM neq NULL}
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
		�� ������� "<strong>{$AR_SEARCH_FIELD_by_proj}</strong>" ������ �� �������. :( �� �� ����� ��������! ���� ��������!
	{/if}
</div>
{/block}