{extends file="info.base.tpl"}
{block name=body}
<div id="content_body">
	<form action="" method="post">
		<div class="add_form">
			<div id="hdr">�������� �������</div>
			{if $ERROR neq ""}<strong class="error" id="error">{$ERROR}</strong>{/if}
			<dl>
				<dt><label for="project_name">��� �������</label></dt>
				<dd><input type="text" name="project_name" id="project_name" value="{$DATA.project_name}"/></dd>
				<dt><label for="description">�������� �������</label></dt>
				<dd><textarea name="description" rows="10" cols="5" id="description">{$DATA.description}</textarea></dd>
				<dt>&nbsp;</dt>
				<dd class="subm"><input type="submit" value="������� ������" /></dd>						
			</dl>
		</div>
	</form>
</div>
{/block}