{extends file="info.base.tpl"}
{block name=body}
<div id="content_body">
	<form action="/search/result/" method="get">
		<div class="add_form fixed_width">		
			<div id="hdr">�����</div>
			<dl>
			<dt>�� ��������:</dt>
			<dd><input type="text" name="by_proj" value="" /></dd>
			<dd class="subm"><input type="submit" value="�����" /></dd>
			</dl>
		</div>
	</form>
</div>
{/block}