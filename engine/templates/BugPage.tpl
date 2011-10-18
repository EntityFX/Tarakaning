{extends file="info.base.tpl"}

{block name=script}
{literal}
		$('.reports_form').checkboxes({titleOn: "�������� ��", titleOff: "����� �������"});
		$('#del').click(function(){
			return confirm('�� ������������� ������� ������� ��������� �����������?');
		});
		
		$("#item_kind, #project_id").change(function(){
			$("#selectProjectForm").submit();
		});
{/literal}
{/block}

{block name=body}
		{* define the function *}
		{function name=bug_type}
		    {if $value eq NEW}new{else if $value eq IDENTIFIED}confirmed{else if $value eq ASSESSED}assigned{else if $value eq RESOLVED}solved{else if $value eq CLOSED}closed{/if}
		{/function}
		
	<div id="content_body">
		<div id="tabs">
			<ul>
				<li><a href="#description"><span>��������</span></a></li>
				<li><a href="#comments"><span>�����������</span></a></li>
				<li><a href="#history"><span>�������</span></a></li>
				<li><a href="#attachments"><span>�����</span></a></li>
			</ul>
			<div id="description">
				<table id="report">
					<col width="250" valign="top" />
					<tbody>
						<tr><td><strong>�</strong></td><td><strong>{$BUG.ID}</strong></td></tr>
						<tr><td><strong>���</strong></td><td><strong>{$BUG.KindN}</strong></td></tr>
						<tr><td><strong>��������� ������</strong></td><td><strong>{$BUG.Title}</strong></td></tr>
						<tr>
							<td><b>������</b></td>
							<td class="{bug_type value=$BUG.Status}">
								{if $CAN_EDIT_REPORT eq TRUE}
									<form method="post" action="">
										<div>
											<select name="state">
												{html_options options=$STATUSES.values selected=$STATUSES.selected}
											</select>
											<input type="submit" value="���������" name="cnange_state" />
										</div>
									</form>
								{else}
									{$BUG.StatusN}
								{/if}
							</td>
						</tr>
						<tr><td><b>��������</b></td><td><a href="/profile/show/{$BUG.UserID}/">{$BUG.NickName}</a></td></tr>
						<tr><td><b>���������</b></td><td>{$BUG.PriorityLevel}</td></tr>
						<tr><td><b>������</b></td><td>{$BUG.ProjectName}</td></tr>
						<tr><td><b>���������</b></td><td>{if $BUG.AssignedTo neq null}<a href="/profile/show/{$BUG.AssignedTo}/">{$BUG.AssignedNickName}</a>{/if}</td></tr>
						{if $BUG.Kind eq Defect}
						<tr><td><b>��� ������</b></td><td>{$BUG.ErrorType}</td></tr>
						{/if}
						<tr><td><b>���� ��������</b></td><td>{$BUG.Time}</td></tr>
						<tr><td><b>��������</b></td><td>{$BUG.Description}</td></tr>
						{if $BUG.Kind eq Defect}
						<tr>
							<td><b>��������, ������� ������� � ������</b></td><td>
							{$BUG.StepsText}
							</td>
						</tr>
						{/if}
					</tbody>

				</table>
			</div>
			<div id="comments">
				{if $ERROR neq ""}
					<strong class="error" id="error">{$ERROR}</strong>
				{/if}
				<div class="groupier">
					<strong>�����������</strong>
					{$COMMENTS_PAGINATOR}
				</div>
			{if $COMMENTS neq NULL}
			<form action="#" class="reports_form" method="post">
				<table class="comments">
					<col width="25" valign="top" />
					<thead>
						<tr>
							<th><input name="del_all" type="checkbox" title="" /></th>
							<th><a href="{$COMMENTS_ORDER.NickName.url}#comments" {if $COMMENTS_ORDER.NickName.order eq true}class="sort"{/if}>������������</a></th>
							<th><a href="{$COMMENTS_ORDER.Comment.url}#comments" {if $COMMENTS_ORDER.Comment.order eq true}class="sort"{/if}>�����������</a></th>
							<th class="date"><a href="{$COMMENTS_ORDER.Time.url}#comments" {if $COMMENTS_ORDER.Time.order eq true}class="sort"{/if}>����</a></th>
						</tr>
					</thead>
					<tbody>
				{foreach name=bugComments from=$COMMENTS item=element} {* ����������� ������ *}
					<tr class="{if $smarty.foreach.bugComments.index % 2 == 0}odd{else}even{/if}">
						<td><input name="del_i[{$element.ID}]" type="checkbox" {if $element.UserID neq $USER_ID}disabled="disabled"{/if}/></td>
						<td><a href="/profile/show/{$element.UserID}/">{$element.NickName}</a></td>
						<td class="left">{$element.Comment}</td>
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
				<strong>������������ ���</strong>
			{/if}
				<div>
					<form action="#comments" method="post">
						<div>
							<dl>
								<dd style="padding-right:4px">
									<textarea style="width: 100%; margin: 15px 0pt;" rows="7" cols="100" name="comment"> </textarea>

								</dd>
								<dd class="subm">
									<input type="submit" name="sendComment" value="�������� �����������"/>
								</dd>
							</dl>
						</div>
					</form>
				</div>
			</div>

			<div id="history">
				<div class="groupier">
					<strong>������� ���������</strong>
				</div>
			{if $HISTORY neq NULL}
				<table class="comments">
					<thead>
						<tr><th>������������</th><th>��������</th><th class="date">����</th></tr>
					</thead>
					<tbody>
						{foreach name=bugHistory from=$HISTORY item=element} {* ����������� ������ *}
						<tr class="odd"><td><a href="#">{$element.UserID}</a></td><td class="left">{$element.Description}</td><td>{$element.OldTime}</td></tr>
						{/foreach}
					</tbody>
				</table>
			{/if}

			</div>
			<div id="attachments">
			</div>
		</div>
	</div>
{/block}