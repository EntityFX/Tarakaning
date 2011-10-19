{extends file="BugsBasePage.tpl"}
{block name=bugs_block}
	<div id="tabs">
		<ul>
			<li><a href="#my_items"><span>��� ������</span></a></li>
			<li><a href="#assigned_items"><span>����������� ���</span></a></li>			
		</ul>
		<div id="my_items">
			{if $MY_BUGS neq NULL}
			<form action="#" class="reports_form" method="post">
				<!--<a class="z" href="#">������� ��</a>-->
				<table class="report_table">
					<thead>
						<tr>
							<th><input name="del_all" type="checkbox" title="" /></th>
							<th><a href="{$MY_BUGS_ORDERER.ID.url}" {if $MY_BUGS_ORDERER.ID.order eq true}class="sort"{/if}>�</a></th>
							<th><a href="{$MY_BUGS_ORDERER.Kind.url}" {if $MY_BUGS_ORDERER.Kind.order eq true}class="sort"{/if}>���</a></th>
							<th><a href="{$MY_BUGS_ORDERER.Status.url}" {if $MY_BUGS_ORDERER.Status.order eq true}class="sort"{/if}>������</a></th>
							<th><a href="{$MY_BUGS_ORDERER.Title.url}" {if $MY_BUGS_ORDERER.Title.order eq true}class="sort"{/if}>���������</a></th>
							<th><a href="{$MY_BUGS_ORDERER.AssignedNickName.url}" {if $MY_BUGS_ORDERER.AssignedNickName.order eq true}class="sort"{/if}>���������</a></th>
							<th><a href="{$MY_BUGS_ORDERER.PriorityLevel.url}" {if $MY_BUGS_ORDERER.PriorityLevel.order eq true}class="sort"{/if}>���������</a></th>
							<th style="width: 180px;"><a href="{$MY_BUGS_ORDERER.Time.url}" {if $MY_BUGS_ORDERER.Time.order eq true}class="sort"{/if}>����</a></th>
						</tr>
					</thead>
					<tbody>
					{foreach name=myBugs from=$MY_BUGS item=element} {* ������� ��� �������*}
						<tr class="{bug_type value=$element.Status}">
						    <td><input name="del_i[{$element.ID}]" type="checkbox" /></td>
							<td><a href="/bug/show/{$element.ID}/" class="sort">{$element.ID}</a></td>
							<td>{$element.KindN}</td>
							<td>{$element.StatusN}</td>
							<td>{$element.Title}</td>
							<td>{if $element.AssignedTo neq null}<a href="/profile/show/{$element.AssignedTo}/">{$element.AssignedNickName}</a>{/if}</td>
							<td>{$element.PriorityLevelN}</td>
							<td>{$element.Time}</td>
						</tr>
					{/foreach}
					</tbody>
				</table>
				<div class="groupier">
					<input type="submit" value="������� ����������" title="������� ����������" name="del" id="del" />
				</div>
			</form>
			{else}
				<strong>��������� ���</strong>
			{/if}
		</div>
		<div id="assigned_items">
			{if $MY_ASSIGNED_BUGS neq NULL}
			<form action="#" class="reports_form" method="post">
				<!--<a class="z" href="#">������� ��</a>-->
				<table class="report_table">
					<thead>
						<tr>
							<th><input name="del_all" type="checkbox" title="" /></th>
							<th><a href="{$MY_ASSIGNED_BUGS_ORDERER.ID.url}#assigned_items" {if $MY_ASSIGNED_BUGS_ORDERER.ID.order eq true}class="sort"{/if}>�</a></th>
							<th><a href="{$MY_ASSIGNED_BUGS_ORDERER.Kind.url}#assigned_items" {if $MY_ASSIGNED_BUGS_ORDERER.Kind.order eq true}class="sort"{/if}>���</a></th>
							<th><a href="{$MY_ASSIGNED_BUGS_ORDERER.Status.url}#assigned_items" {if $MY_ASSIGNED_BUGS_ORDERER.Status.order eq true}class="sort"{/if}>������</a></th>
							<th><a href="{$MY_ASSIGNED_BUGS_ORDERER.Title.url}#assigned_items" {if $MY_ASSIGNED_BUGS_ORDERER.Title.order eq true}class="sort"{/if}>���������</a></th>
							<th><a href="{$MY_ASSIGNED_BUGS_ORDERER.AssignedNickName.url}#assigned_items" {if $MY_ASSIGNED_BUGS_ORDERER.AssignedNickName.order eq true}class="sort"{/if}>���������</a></th>
							<th><a href="{$MY_ASSIGNED_BUGS_ORDERER.PriorityLevel.url}#assigned_items" {if $MY_ASSIGNED_BUGS_ORDERER.PriorityLevel.order eq true}class="sort"{/if}>���������</a></th>
							<th style="width: 180px;"><a href="{$MY_ASSIGNED_BUGS_ORDERER.Time.url}#assigned_items" {if $MY_ASSIGNED_BUGS_ORDERER.Time.order eq true}class="sort"{/if}>����</a></th>
						</tr>
					</thead>
					<tbody>
					{foreach name=myAssignedBugs from=$MY_ASSIGNED_BUGS item=element} {* ������� ��� �������*}
						<tr class="{bug_type value=$element.Status}">
						    <td><input name="del_i[{$element.ID}]" type="checkbox" /></td>
							<td><a href="/bug/show/{$element.ID}/" class="sort">{$element.ID}</a></td>
							<td>{$element.KindN}</td>
							<td>{$element.StatusN}</td>
							<td>{$element.Title}</td>
							<td>{if $element.AssignedTo neq null}<a href="/profile/show/{$element.AssignedTo}/">{$element.AssignedNickName}</a>{/if}</td>
							<td>{$element.PriorityLevelN}</td>
							<td>{$element.Time}</td>
						</tr>
					{/foreach}
					</tbody>
				</table>
				<div class="groupier">
					<input type="submit" value="������� ����������" title="������� ����������" name="del" id="del" />
				</div>
			</form>
			{else}
				<strong>��������� ���</strong>
			{/if}
		</div>
	</div>
{/block}

