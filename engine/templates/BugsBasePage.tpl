{extends file="info.base.tpl"}

{block name=script}
{literal}
		$('.reports_form').checkboxes({titleOn: "�������� ��", titleOff: "����� �������"});
		$('#del').click(function(){
			return confirm('�� ������������� ������� ������� ���������� ��������?');
		});
		
		$("#item_kind, #project_id").change(function(){
			$("#selectProjectForm").submit();
		});
{/literal}
{/block}

{block name=body}
		{* define the function *}
		{function name=bug_type}
		    {if $value eq NEW}new{else if $value eq IDENTIFIED}confirmed{else if $value eq ASSESSED}assigned{else if $value eq RESOLVED}solved{else if $value eq CLOSED}closed{/if}
		{/function}
<div id="content_body">
	{if $PROJECTS.PROJECTS_LIST neq NULL}
		<div class="groupier">
			
			<form action="#" id="selectProjectForm">
				<div>
				<label for="project_id">������</label>
				<select id="project_id" name="project_id">
				{if $PROJECTS.PROJECTS_LIST neq NULL}
					{html_options options=$PROJECTS.PROJECTS_LIST selected=$PROJECTS.selected}
				{/if}
				</select>
				<label for="item_kind">�������� </label> 
				<select id="item_kind" name="item_kind">
					{html_options values=$ITEM_KIND.values output=$ITEM_KIND.text selected=$ITEM_KIND.selected}
				</select>
				</div>
			</form>
			<form action="/bug/add/" method="post"><div>
				<input type="submit" value="�������� �����" title="�������� ����� ����� �� ������" name="add"/>
			</div></form>
			{$PROJECT_BUGS_PAGINATOR}
		</div>
		<div>
			{block name=bugs_block}{/block}
		</div>
	{else}
		<strong>� ��� ��� ��������. �������� ��� ����������� �� ������.</strong>
	{/if}
</div>
{/block}