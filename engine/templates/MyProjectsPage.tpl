{extends file="InfoBasePage.base.tpl"}
{block name=script}
{literal}
		$('.reports_form').checkboxes({titleOn: "�������� ��", titleOff: "����� �������"});
		$('#del').click(function(){
			return confirm('�� ������������� ������� ������� �������? ��� ������� � �������� ���� �����, ������������, � ����� �����������');
		});
		
		$("#item_kind, #project_id").change(function(){
			$("#selectProjectForm").submit();
		});
{/literal}
{/block}
{block name=body}
<div id="content_body">
	<div id="tabs">
		<ul>
			<li><a href="#my_project"><span>��� �������</span></a></li>
			<li><a href="#all_projects"><span>��� �������</span></a></li>			
		</ul>
		<div id="my_project">
			<div class="groupier">
				<a href="/my/project/new/">������� ����� ������</a>
				{$MY_PROJECTS_PAGINATOR}
			</div>
			{if $MY_PROJECTS neq NULL}
			<form action="#" class="reports_form" method="post">
				<table class="projects_table">
					<col width="23" />
					<thead> 
						<tr>
						  <th><input name="del" type="checkbox" /></th>
						  <th><a href="{$MY_PROJECTS_ORDERER.ProjectName.url}" {if $MY_PROJECTS_ORDERER.Name.order eq true}class="sort"{/if}>������</a></th>
						  <th><a href="{$MY_PROJECTS_ORDERER.Description.url}" {if $MY_PROJECTS_ORDERER.Description.order eq true}class="sort"{/if}>���������</a></th>
						  <th colspan="5">�������</th>
						  <th><a href="{$MY_PROJECTS_ORDERER.CountSubscribeRequests.url}" {if $MY_PROJECTS_ORDERER.CountSubscribeRequests.order eq true}class="sort"{/if}>������</a></th>
						  <th><a href="{$MY_PROJECTS_ORDERER.CountUsers.url}" {if $MY_PROJECTS_ORDERER.CountUsers.order eq true}class="sort"{/if}>����������</a></th>
						  <th><a href="{$MY_PROJECTS_ORDERER.CreateDateTime.url}" {if $MY_PROJECTS_ORDERER.CreateDateTime.order eq true}class="sort"{/if}>���� ��������</a></th>
						</tr>
					</thead> 
					<tbody>
				{foreach name=myProjects from=$MY_PROJECTS item=element} {* ������� ��� �������*}
						<tr class="{if $smarty.foreach.myProjects.index % 2 == 0}odd{else}even{/if}">
							<td><input name="del_i[{$element.ProjectID}]" type="checkbox" /></td>
							<td><a href="/my/project/show/{$element.ProjectID}/">{$element.ProjectName}</a><br />
							</td>
							<td>{$element.Description}</td>
							<td class="new">{$element.NEW}</td><td class="confirmed">{$element.IDENTIFIED}</td><td class="assigned">{$element.ASSESSED}</td><td class="solved">{$element.RESOLVED}</td><td class="closed">{$element.CLOSED}</td>
							<td><strong {if $element.CountSubscribeRequests neq 0}class="strongest"{/if}>{$element.CountSubscribeRequests}</strong></td>
							<td>{$element.CountUsers}</td>
							<td>{$element.CreateDateTime}</td>
						</tr>
				{/foreach}
					</tbody>
			  	</table>
				<div class="groupier">
					<input value="�������" name="del" id="del" type="submit" />
				</div>
			</form>
			{else}
				<span>�������� ���</span>
			{/if}
		</div>
		<div id="all_projects">
			<div class="groupier">
				{$MEMBER_PROJECTS_PAGINATOR}
			</div>
			{if $PROJECTS_WITHOUT_ME neq NULL}
			 <table class="projects_table">
				<thead> 
					<tr>
						<th><a href="{$MEMBER_PROJECTS_ORDERER.ProjectName.url}#all_projects" {if $MEMBER_PROJECTS_ORDERER.ProjectName.order eq true}class="sort"{/if}>������</a></th>
						<th><a href="{$MEMBER_PROJECTS_ORDERER.Description.url}#all_projects" {if $MEMBER_PROJECTS_ORDERER.Description.order eq true}class="sort"{/if}>��������</a></th>
						<th><a href="{$MEMBER_PROJECTS_ORDERER.OwnerNickName.url}#all_projects" {if $MEMBER_PROJECTS_ORDERER.OwnerNickName.order eq true}class="sort"{/if}>��������</a></th>
						<th colspan="5">�������</th>
                        <th><a href="{$MEMBER_PROJECTS_ORDERER.CountUsers.url}" {if $MEMBER_PROJECTS_ORDERER.CountUsers.order eq true}class="sort"{/if}>����������</a></th>   
						<th><a href="{$MEMBER_PROJECTS_ORDERER.CreateDateTime.url}#all_projects" {if $MEMBER_PROJECTS_ORDERER.CreateDateTime.order eq true}class="sort"{/if}>����</a></th>
					</tr>
				</thead> 
				<tbody>
				{foreach name=notMyProjects from=$PROJECTS_WITHOUT_ME item=element} {* ������� ��� �������*}
				  <tr class="{if $smarty.foreach.notMyProjects.index % 2 == 0}odd{else}even{/if}">
					<td><a href="/my/project/show/{$element.ProjectID}/">{$element.ProjectName}</a></td>
					<td>{$element.Description}</td>
					<td><a href="#">{$element.OwnerNickName}</a></td>
					<td class="new">{$element.NEW}</td><td class="confirmed">{$element.IDENTIFIED}</td><td class="assigned">{$element.ASSESSED}</td><td class="solved">{$element.RESOLVED}</td><td class="closed">{$element.CLOSED}</td>
					<td>{$element.CountUsers}</td> 
                    <td>{$element.CreateDateTime}</td>
				  </tr>
				{/foreach}
				</tbody>
			</table>
			{else}
				<span>�������� ���</span>
			{/if}
			</div>
		</div>
	</div>

{/block}