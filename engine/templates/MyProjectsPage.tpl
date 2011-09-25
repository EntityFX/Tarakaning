{extends file="info.base.tpl"}
{block name=body}
<div id="content_body">
	<div id="tabs">
		<ul>
			<li><a href="#my_project"><span>��� �������</span></a></li>
			<li><a href="#all_projects"><span>��� �������</span></a></li>			
		</ul>
		<div id="my_project">
			{if $GOOD eq TRUE}
				<strong class="ok" id="good">������ ������� ������</strong>
			{/if}
			<div class="groupier">
				<a href="/my/project/new/">������� ����� ������</a>
				{$MY_PROJECTS_PAGINATOR}
			</div>
			{if $MY_PROJECTS neq NULL}
			<form action="#" class="reports_form">
				<table class="projects_table">
					<col width="23" />
					<thead> 
						<tr>
						  <th><input name="del" type="checkbox" /></th>
	
						  <th><a href="#">������</a></th>
						  <th><a href="#">���������</a></th>
						  <th colspan="5"><a href="#">�������</a></th>
						  <th><a href="#">������</a></th>
						  <th><a href="#">�������������</a></th>
						  <th><a href="#">���� ��������</a></th>
						</tr>
					</thead> 
					<tbody>
				{foreach name=myProjects from=$MY_PROJECTS item=element} {* ������� ��� �������*}
						<tr class="{if $smarty.foreach.myProjects.index % 2 == 0}odd{else}even{/if}">
							<td><input name="delId" type="checkbox" /></td>
							<td><a href="/my/project/show/{$element.ProjectID}/">{$element.Name}</a><br />
							</td>
							<td>{$element.Description}</td>
							<td class="new">{$element.NEW}</td><td class="confirmed">{$element.IDENTIFIED}</td><td class="assigned">{$element.ASSESSED}</td><td class="solved">{$element.RESOLVED}</td><td class="closed">{$element.CLOSED}</td>
							<td><strong {if $element.CountRequests neq 0}class="strongest"{/if}>{$element.CountRequests}</strong></td>
							<td>{$element.CountUsers}</td>
							<td>{$element.CreateDate}</td>
						</tr>
				{/foreach}
					</tbody>
			  	</table>
				<div class="groupier">
					<input value="�������" name="delBtn" type="button" />
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
						<th><a href="#">������</a></th>
						<th><a href="#">���������</a></th>
						<th><a href="#">��������</a></th>
						<th colspan="5"><a href="#">�������</a></th>

						<th><a href="#">������</a></th>
						<th><a href="#">����</a></th>
					</tr>
				</thead> 
				<tbody>
				{foreach name=notMyProjects from=$PROJECTS_WITHOUT_ME item=element} {* ������� ��� �������*}
				  <tr class="{if $smarty.foreach.notMyProjects.index % 2 == 0}odd{else}even{/if}">
					<td><a href="/my/project/show/{$element.ProjectID}/">{$element.Name}</a></td>
					<td>{$element.Description}</td>
					<td><a href="#">Sudo777</a></td>
					<td class="new">{$element.NEW}</td><td class="confirmed">{$element.IDENTIFIED}</td><td class="assigned">{$element.ASSESSED}</td><td class="solved">{$element.RESOLVED}</td><td class="closed">{$element.CLOSED}</td>
					<td><strong {if $element.CountRequests neq 0}class="strongest"{/if}>{$element.CountRequests}</strong></td>
					<td>{$element.CreateDate}</td>
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