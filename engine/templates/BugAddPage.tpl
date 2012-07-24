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
{if $ERROR neq ""}
    <div class="alert alert-error" id="error">
        <a class="close" data-dismiss="alert" href="#">&times;</a>
        {$ERROR}
    </div>
{/if}
<form class="form-horizontal" action="" method="post">
    <fieldset>
        <legend>�������� ��������</legend>
        <div class="control-group">
            <label class="control-label" for="bug_project_id">�������� ������</label>
            <div class="controls">
                <select id="bug_project_id" name="bug_project_id">
                    {if $PROJECTS.PROJECTS_LIST neq NULL}
                        {html_options options=$PROJECTS.PROJECTS_LIST selected=$PROJECTS.selected}
                    {/if}
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="title">���������</label>
            <div class="controls">
                <input type="text" class="input-xlarge" id="title" name="title" value="{$DATA.title}" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="item_type">���</label>
            <div class="controls">
                <select id="item_type" name="item_type" style="font-weight: bold;">
                    <option value="Task">������</option>
                    <option value="Defect">������</option>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="error_type">��� ������</label>
            <div class="controls">
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
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="priority">���������</label>
            <div class="controls">
                <select id="priority" name="priority">
                    <option value="0">������</option>
                    <option value="1" selected="selected">�������</option>
                    <option value="2">������</option>
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="assigned_to">���������</label>
            <div class="controls">
                <select id="assigned_to" name="assigned_to">
                    <option value="">-</option>
                </select>
            </div>
        </div> 
        <div class="control-group">
            <label class="control-label" for="hour_req">��������� �� ������</label>
            <div class="controls">
                <input type="text" id="hour_req" name="hour_req" value="{$DATA.hour_req}" maxlength="5" size="5" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="description">��������</label>
            <div class="controls">
                <textarea id="description" name="description" class="input-xxlarge" rows="7" >{$DATA.description}</textarea>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="steps">��������, ������� ������� � ������</label>
            <div class="controls">
                <textarea id="steps" name="steps" class="input-xxlarge" rows="10">{$DATA.steps}</textarea>
            </div>
        </div>
        <div class="form-actions">
            <input class="btn btn-primary" type="submit" name="add_report" value="������� �������" />
        </div>
    </fieldset>
</form>
{/block}