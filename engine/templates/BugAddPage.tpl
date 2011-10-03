{extends file="info.base.tpl"}
{block name=script}
<script type="text/javascript">
/* <![CDATA[ */
	$(function(){
		$("#tabs").tabs();
		$("input:button, input:submit, button, .groupier a, .groupier li span, #exit").button();

		if ($("#item_type").val()=="Task") $(".for_defect").hide();
		$("#item_type").change(function(){
			switch ($(this).val())
			{
				case "Task":
					$(".for_defect").hide();
					break;
				case "Defect":
					$(".for_defect").show();
					break;
			}
		});
	});
/* ]]>*/
</script>
{/block}
{block name=body}
	<div id="content_body">
		<form action="" method="post">
			<div class="add_form">
				<div id="hdr">���������� ������ �� ������</div>
				{if $ERROR neq ""}<strong class="error" id="error">{$ERROR}</strong>{/if}
				<dl>
					<dt><label for="project_id">�������� ������</label></dt>
					<dd>
						<select id="project_id" name="project_id">
							{if $PROJECTS_LIST neq NULL}
							{foreach from=$PROJECTS_LIST item=element} {* ������� ��� ������� ��� � �� ������*}
							<option value="{$element.ProjectID}">{$element.Name}</option>
							{/foreach}
							{/if}
						</select>
					</dd>
					<dt><label for="title">������� ���������</label></dt>
					<dd><input type="text" id="title" name="title" value="{$DATA.title}" /></dd>
					<dt><label for="item_type">���</label></dt>
					<dd>									
						<select id="item_type" name="item_type" style="font-weight: bold;">
							<option value="Task">������</option>
							<option value="Defect">������</option>
						</select>
					</dd>
					<dt class="for_defect"><label for="error_type">��� ������</label></dt>
					<dd class="for_defect">									
						<select id="error_type" name="error_type">
							<option value="Crash">����</option>
							<option value="Cosmetic">�������������</option>
							<option value="Exception">����������</option>
							<option value="Functional">��������������</option>
							<option value="Minor">��������������</option>
							<option selected="selected" value="Major">������</option>
							<option value="Install">������ ���������</option>
							<option value="Block">�����������</option>
						</select>
					</dd>
					<dt><label for="priority">���������</label></dt>
					<dd>									
						<select id="priority" name="priority">
							<option value="0">������</option>
							<option value="1" selected="selected">�������</option>
							<option value="2">������</option>
						</select>
					</dd>
					<dt><label for="asigned_to">���������</label></dt>
					<dd>									
						<select id="asigned_to" name="asigned_to">
							<option value="0"></option>
						</select>
					</dd>
					<dt><label for="description">��������</label></dt>
					<dd><textarea id="description" name="description" rows="7" cols="20" >{$DATA.description}</textarea></dd>
					<dt class="for_defect"><label for="steps">��������, ������� ������� � ������</label></dt>
					<dd class="for_defect"><textarea id="steps" name="steps" rows="10" cols="20" >{$DATA.steps}</textarea></dd>
					<dt>&nbsp;</dt>
					<dd class="subm"><input type="submit" name="add_report" value="������� �����" /></dd>						
				</dl>
			</div>
		</form>
	</div>
{/block}