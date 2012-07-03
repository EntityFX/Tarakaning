{extends file="BugsBasePage.tpl"}
{block name=bugs_block}
				{if $MY_BUGS neq NULL}
				<form action="#" class="reports_form" method="post">
					<!--<a class="z" href="#">������� ��</a>-->
					<input type="hidden" name="cur_project_id" value="{$PROJECTS.selected}" />
					<table class="report_table">
						<thead>
							<tr>
								<th><input name="del_all" type="checkbox" title="" /></th>
								<th><a href="{$MY_BUGS_ORDERER.ID.url}" {if $MY_BUGS_ORDERER.ID.order eq true}class="sort"{/if}>�</a></th>
								<th><a href="{$MY_BUGS_ORDERER.Kind.url}" {if $MY_BUGS_ORDERER.Kind.order eq true}class="sort"{/if}>���</a></th>
								<th><a href="{$MY_BUGS_ORDERER.Status.url}" {if $MY_BUGS_ORDERER.Status.order eq true}class="sort"{/if}>������</a></th>
								<th><a href="{$MY_BUGS_ORDERER.Title.url}" {if $MY_BUGS_ORDERER.Title.order eq true}class="sort"{/if}>���������</a></th>
								<th><a href="{$MY_BUGS_ORDERER.PriorityLevel.url}" {if $MY_BUGS_ORDERER.PriorityLevel.order eq true}class="sort"{/if}>���������</a></th>
								<th><a href="{$MY_BUGS_ORDERER.NickName.url}" {if $MY_BUGS_ORDERER.NickName.order eq true}class="sort"{/if}>��������</a></th>
								<th><a href="{$MY_BUGS_ORDERER.AssignedNickName.url}" {if $MY_BUGS_ORDERER.AssignedNickName.order eq true}class="sort"{/if}>���������</a></th>
								<th colspan="2">����� (�)</th>
								<th style="width: 180px;"><a href="{$MY_BUGS_ORDERER.CreateDateTime.url}" {if $MY_BUGS_ORDERER.CreateDateTime.order eq true}class="sort"{/if}>����</a></th>
							</tr>
						</thead>
						<tbody>  
						{foreach name=myBugs from=$MY_BUGS item=element} {* ������� ��� �������*}
							<tr class="{bug_type value=$element.Status}">    
							    <td><input name="del_i[{$element.ID}]" type="checkbox" {if ($LOGIN neq $element.NickName) and ($PROJECT_OWNER neq $USER_ID)}disabled="disabled"{/if}/></td>
								<td><a href="/bug/show/{$element.ID}/" class="sort">{$element.ID}</a></td>
								<td>{$element.KindN}</td>
								<td>{$element.StatusN}</td>
								<td>{$element.Title}</td>
								<td>{$element.PriorityLevelN}</td>
								<td><a href="/profile/show/{$element.UserID}/">{$element.NickName}</a></td>
								<td><a href="/profile/show/{$element.AssignedTo}/">{$element.AssignedNickName}</a></td>
								<td>{$element.HoursRequired}</td><td>{$element.HoursFact}</td>
								<td>{$element.CreateDateTime}</td>
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
{/block}