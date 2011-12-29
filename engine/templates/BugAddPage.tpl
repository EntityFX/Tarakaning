{extends file="InfoBasePage.base.tpl"}
{block name=script}
	{literal}
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
		
		function updateProjectUsers(projectID)
		{
			$.getJSON(
				"/bug/ajax/",
				{project_id: projectID},
				function(dataResult)
				{
					var usersList='<option value=" ">-</option>';

					$.each(dataResult, function(key,val){
						usersList+='<option value="' + val.UserID + '">' + val.NickName + '</option>';
					});
					
					$("#assigned_to").empty().append(usersList);
				}
			);
		}
		
		updateProjectUsers($("#bug_project_id").val());
		$("#bug_project_id").change(function(){
			var projectID=$(this).val();
			updateProjectUsers(projectID);
		});
	{/literal}
{/block}
{block name=body}
	<div id="content_body">
		{if $ERROR neq ""}
		<div class="messageBox errorBox">
			<strong class="error" id="error">{$ERROR}</strong>
		</div> 
		{/if}
		<form action="" method="post">
			<div class="add_form">
				<div id="hdr">���������� ������ �� ������</div>
				<dl>
					<dt><label for="bug_project_id">�������� ������</label></dt>
					<dd>
						<select id="bug_project_id" name="bug_project_id">
						{if $PROJECTS.PROJECTS_LIST neq NULL}
							{html_options options=$PROJECTS.PROJECTS_LIST selected=$PROJECTS.selected}
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
					<dt><label for="assigned_to">���������</label></dt>
					<dd>									
						<select id="assigned_to" name="assigned_to">
							<option value="">-</option>
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