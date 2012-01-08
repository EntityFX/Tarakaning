{extends file="InfoBasePage.base.tpl"}
{block name=script}
{literal}
		$('.reports_form').checkboxes({titleOn: "�������� ��", titleOff: "����� �������"});
		$('#del').click(function(){
			return confirm('�� ������������� ������� �������� ������?');
		});
{/literal}
{/block}
{block name=body}
<div id="content_body">
	{if $MY_SUBSCRIBES_LIST neq NULL}
		{if MY_SUBSCRIBES_PAGINATOR neq ''}
		<div class="groupier">
			{$MY_SUBSCRIBES_PAGINATOR}
		</div>
		{/if}
		<form action="#" class="reports_form" method="post">
			<table class="projects_table">
				<col width="23" />
				<thead> 
					<tr>
					  <th><input name="del" type="checkbox" /></th>
					  <th><a href="{$MY_SUBSCRIBES_ORDERER.ProjectName.url}" {if $MY_SUBSCRIBES_ORDERER.ProjectName.order eq true}class="sort"{/if}>������</a></th>
					  <th><a href="{$MY_SUBSCRIBES_ORDERER.ProjectOwner.url}" {if $MY_SUBSCRIBES_ORDERER.ProjectOwner.order eq true}class="sort"{/if}>��������</a></th>
					  <th><a href="{$MY_SUBSCRIBES_ORDERER.RequestTime.url}" {if $MY_SUBSCRIBES_ORDERER.RequestTime.order eq true}class="sort"{/if}>���� ������</a></th>
					</tr>
				</thead> 
				<tbody>
			{foreach name=myProjects from=$MY_SUBSCRIBES_LIST item=element} {* ������� ��� �������*}
					<tr class="{if $smarty.foreach.myProjects.index % 2 == 0}odd{else}even{/if}">
						<td><input name="del_i[{$element.ID}]" type="checkbox" /></td>
						<td>{$element.ProjectName}</td>
						<td><a href="/profile/show/{$element.OwnerID}/">{$element.ProjectOwner}</a></td>
						<td>{$element.RequestTime}</td>
					</tr>
			{/foreach}
				</tbody>
		  	</table>
			<div class="groupier">
				<input value="�������� ������" name="del" id="del" type="submit" />
			</div>
		</form>
	{else}
		<span>�� �� ������ �� ����� ������</span>
	{/if}
</div>

{/block}