{extends file="info.base.tpl"}
{block name=body}
<div id="content_body"> 
	<div id="tabs"> 
		<ul> 
			<li><a href="#about"><span>��������</span></a></li> 
			<li><a href="#users"><span>���������</span></a></li> 
			<li><a href="#requests"><span>������</span></a></li>			
		</ul> 
		<div id="about" class="info_div">			
			<dl> 
				<dt>��������</dt> 
				<dd>{$Project.Name}</dd> 
				<dt>�����</dt> 
				<dd><a href="/profile/show/{$Project.OwnerID}/">{$Project.NickName}</a></dd>				
				<dt>��������</dt> 
				<dd>{$Project.Description}&nbsp;</dd> 
			</dl> 
			<form action="/my/project/edit/" method="post">
				<div>
					<input type="submit" value="������������� ������" />
					<input type="hidden" name="project_id" value="{$Project.ProjectID}" />
				</div>
			</form> 
		</div> 
		<div id="users"> 
			<div class="groupier"> 
				{$MY_PROJECT_DETAIL_PAGINATOR}
			</div>
			{if $PROJECT_USERS neq NULL}
			<form action="#" class="reports_form"> 
				<table class="projects_table"> 
					<col width="23" /> 
					<thead> <tr> 
						<th><input name="del" type="checkbox" /></th> 
						<th><a href="{$MY_PROJECT_ORDERER.NickName.url}#users" {if $MY_PROJECT_ORDERER.NickName.order eq true}class="sort"{/if}>������������</a></th> 
						<th><a href="{$MY_PROJECT_ORDERER.CountCreated.url}#users" {if $MY_PROJECT_ORDERER.CountCreated.order eq true}class="sort"{/if}>�������</a></th> 
						<th><a href="#">���������� ������������</a></th> 
						<th colspan="5"><a href="{$MY_PROJECT_ORDERER.CountErrors.url}#users" {if $MY_PROJECT_ORDERER.CountErrors.order eq true}class="sort"{/if}>�����������</a></th> 
					</tr> 
					</thead> 
					<tbody> 
					{foreach name=projectUsers from=$PROJECT_USERS item=element} {* ������� ��� �������*}
						<tr class="{if $smarty.foreach.projectUsers.index % 2 == 0}odd{else}even{/if}"> 
							<td><input name="delId" type="checkbox" /></td> 
							<td>{if $element.Owner eq 1}<strong><a href="/profile/show/{$element.UserID}/">{$element.NickName}</a></strong>&nbsp;<sup style="font-size: 10px; color: red;">(��������)</sup>{else}<a href="/profile/show/{$element.UserID}/">{$element.NickName}</a>{/if}</td> 
							<td>{$element.CountCreated}</td> 
							<td><a href="#">213</a></td> 
							<td class="new">{$element.NEW}</td><td class="confirmed">{$element.IDENTIFIED}</td><td class="assigned">{$element.ASSESSED}</td><td class="solved">{$element.RESOLVED}</td><td class="closed">{$element.CLOSED}</td>
						</tr> 
					{/foreach}
					</tbody> 
				</table> 
				<div class="groupier"> 
					<input value="������� ���������� �����������" name="delBtn" type="button" /> 
				</div> 
			</form> 
			{/if} 
		</div> 
		<div id="requests"> 
			<form action="#" class="reports_form"> 
			  <table class="projects_table"> 
				<col width="23" /> 
				<col /> 
				<col width="250" /> 
				<thead> <tr> 
				  <th><input name="del" type="checkbox" /></th> 
				  <th><a href="#">������������</a></th> 
				  <th><a href="#">��������</a></th> 
				</tr> 
				</thead> <tbody> 
				  <tr class="odd"> 
					<td><input name="delId" type="checkbox" /></td> 
					<td><a href="#">EntityFX</a></td> 
					<td><button type="button">�����������</button><button type="button">��������</button></td> 
				  </tr> 
				  <tr class="even"> 
					<td><input name="delId" type="checkbox" /></td> 
					<td><a href="#">Flood</a></td> 
					<td><button type="button">�����������</button><button type="button">��������</button></td> 
				  </tr> 
				  <tr class="odd"> 
					<td><input name="delId" type="checkbox" /></td> 
					<td>����</td> 
					<td><button type="button">�����������</button><button type="button">��������</button></td> 
				  </tr> 
				  <tr class="even"> 
					<td><input name="delId" type="checkbox" /></td> 
					<td><a href="#">Sudo777</a><br /> 
					</td> 
					<td><button type="button">�����������</button><button type="button">��������</button></td> 
				  </tr> 
				  <tr class="odd"> 
					<td><input name="delId" type="checkbox" /></td> 
					<td><a href="#">Sudo777</a></td> 
					<td><button type="button">�����������</button><button type="button">��������</button></td> 
				  </tr> 
				</tbody> 
			  </table> 
				<div class="groupier"> 
					<input value="����������� ������" name="deSubscr" type="button" /> 
					<input value="������� ������" name="delBtn" type="button" /> 
				</div> 
			</form> 
		</div> 
    </div> 
</div>
{/block}