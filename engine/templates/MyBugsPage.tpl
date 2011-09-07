{extends file="info.base.tpl"}

{block name=body}
		{* define the function *}
		{function name=bug_type}
		    {if $value eq NEW}new{else if $value eq IDENTIFIED}confirmed{else if $value eq ASSESSED}assigned{else if $value eq RESOLVED}solved{else if $value eq CLOSED}closed{/if}
		{/function}

<div id="content_body">

		<div class="groupier">
			<form action="#" id="selectProjectForm">
				<div>
				<label for="project_id">������</label>
				<select id="project_id" name="project_id">
					{if $PROJECTS_LIST neq NULL}
					{foreach from=$PROJECTS_LIST item=element} {* ������� ��� ������� ��� � �� ������*}
					<option value="{$element.ProjectID}">{$element.Name}</option>
					{/foreach}
					{/if}
				</select>
				</div>
			</form>
			<form action="/bug/add/" method="post"><div>
				<input type="submit" value="�������� �����" title="�������� ����� ����� �� ������" name="add"/>
			</div></form>
			<ul>
				<li><a href="#">&lt;&lt;</a></li>
				<li><a href="#">&lt;</a></li>
				<li><a href="#">6</a></li>

				<li><span style="font-weight: bold; color: #a88; border-color: #a80; background: #d5d597 !important;">7</span></li>
				<li><a href="#">8</a></li>
				<li><a href="#">9</a></li>
				<li><a href="#">10</a></li>
				<li><a href="#">&gt;</a></li>
				<li><a href="#">&gt;&gt;</a></li>						
			</ul>
		</div>

	
			<div>
				{if $MY_BUGS neq NULL}
				<form action="#" class="reports_form">
					<!--<a class="z" href="#">������� ��</a>-->
					<table class="report_table">
						<thead>
							<tr>
								<th><input name="del_all" type="button" value="" title="" style="width:18px; padding: 0px; height: 18px;" /></th>
								<th><a href="#" class="sort">� &uarr;</a></th>
								<th><a href="#">������</a></th>
								<th><a href="#">���������</a></th>
								<th><a href="#">���������</a></th>
								<th><a href="#">���������</a></th>
								<th><a href="#">���</a></th>
								<th><a href="#">����</a></th>

							</tr>
						</thead>
						<tbody>
						{foreach name=myBugs from=$MY_BUGS item=element} {* ������� ��� �������*}
							<tr class="{bug_type value=$element.Status}">
							    <td><input name="del1" type="checkbox" /></td>
								<td><a href="/bug/show/{$element.ID}/" class="sort">{$element.ID}</a></td>
								<td>{$element.StatusN}</td>
								<td>{$element.Title}</td>
								<td><a href="#">Ignatty</a></td>
								<td>{$element.PriorityLevel}</td>
								<td>{$element.ErrorType}</td>
								<td>{$element.Time}</td>
							</tr>
						{/foreach}
						</tbody>
					</table>
					<div class="groupier">
						<input type="submit" value="������� ����������" title="������� ����������" name="del" />
					</div>
				</form>
				{else}
					<strong>����� ���</strong>
				{/if}
			</div>
</div>
{/block}

