{extends file="InfoBasePage.base.tpl"}

{block name=script}
{literal}
		$("a.button, span.button").button();
        
        $('.reports_form').checkboxes({titleOn: "Отметить всё", titleOff: "Снять отметки"});
		$('#del').click(function(){
			return confirm('Вы действительно желаете удалить выбранные комментарии?');
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
				<li><a href="#description"><span>Описание</span></a></li>
				<li><a href="#comments"><span>Комментарии</span></a></li>
				<li><a href="#history"><span>История</span></a></li>
				<li><a href="#attachments"><span>Файлы</span></a></li>
			</ul>
			<div id="description">
				{if $CAN_EDIT_DATA eq TRUE}
					<form action="" method="post">
						<div class="add_form">
							<div id="hdr">{$BUG.KindN} <strong>№ {$BUG.ID}</strong>
                                {if $ITEM_PREV_ID neq NULL}
                                    <a class="button" href="/bug/show/{$ITEM_PREV_ID}" title="Предыдущий элемент"><</a>
                                {else}
                                    <span class="button ui-button-disabled ui-state-disabled" title="Предыдущий элемент"><</span>
                                {/if}
                                {if $ITEM_NEXT_ID neq NULL}
                                    <a class="button" href="/bug/show/{$ITEM_NEXT_ID}" title="Следующий элемент">></a>
                                {else}
                                    <span class="button ui-button-disabled ui-state-disabled" title="Следующий элемент">></span>
                                {/if}
                                <a class="button" href="/bug/add/" title="Создать элемент">*</a>
                                <a class="button" href="#" title="Удалить элемент">x</a>
                            </div>
							{if $ERROR neq ""}<strong class="error" id="error">{$ERROR}</strong>{/if}
							<dl>
								<dt>№</dt><dd>{$BUG.ID}</dd>
								<dt><label for="title">Заголовок</label></dt><dd><input type="text" id="title" name="title" value="{$BUG.Title}" /></dd>
								<dt>Проект</dt><dd>{$BUG.ProjectName}</dd>
								<dt>Владелец</dt><dd><a href="/profile/show/{$BUG.UserID}/">{$BUG.NickName}</a></dd>
								<dt>Дата создания</dt><dd>{$BUG.CreateDateTime}</dd>
								<dt>Тип</dt><dd>{$BUG.KindN}</dd>
								<dt><label style="padding: 2px;">Статус</label></dt>
								<dd>
									<div class="{bug_type value=$BUG.Status}" style="padding: 2px;">
									<select name="state">
										{html_options options=$STATUSES.values selected=$STATUSES.selected}
									</select>
									</div>
								</dd>
								<dt><label for="priority">Приоритет</label></dt>
								<dd>
									<select id="priority" name="priority">
										{html_options options=$PRIORITY_LEVEL.values selected=$PRIORITY_LEVEL.selected}
									</select>
								</dd>
								<dt><label for="assigned_to">Назначено</label></dt>
								<dd>									
									<select id="assigned_to" name="assigned_to">
										<option value="">-</option>
										{html_options options=$USERS_ASSIGN_TO selected=$BUG.AssignedTo}
									</select>
								</dd>
								{if $BUG.Kind eq Defect}
								<dt class="for_defect"><label for="error_type">Вид ошибки</label></dt>
								<dd class="for_defect">									
									<select id="error_type" name="error_type">
										{html_options options=$DEFECT_TYPE.values selected=$DEFECT_TYPE.selected}
									</select>
								</dd>
								{/if}
								<dt class="for_defect"><label for="descr">Описание</label></dt>
								<dd class="for_defect"><textarea id="descr" name="descr" rows="10" cols="20" >{$BUG.Description}</textarea></dd>
								{if $BUG.Kind eq Defect}
								<dt class="for_defect"><label for="steps">Действия, которые привели к ошибке</label></dt>
								<dd class="for_defect"><textarea id="steps" name="steps" rows="10" cols="20" >{$BUG.StepsText}</textarea></dd>
								{/if}
								<dt>&nbsp;</dt>
								<dd class="subm"><input type="submit" name="cnange_state" value="Сохранить изменения" /></dd>						
							</dl>
						</div>
					</form>
				{else}
					<form action="" method="post">	
						<div class="add_form">
							<div id="hdr">{$BUG.KindN} <strong>№ {$BUG.ID}</strong>
                                {if $ITEM_PREV_ID neq NULL}
                                    <a class="button" href="/bug/show/{$ITEM_PREV_ID}" title="Предыдущий элемент"><</a>
                                {else}
                                    <span class="button ui-button-disabled ui-state-disabled" title="Предыдущий элемент"><</span>
                                {/if}
                                {if $ITEM_NEXT_ID neq NULL}
                                    <a class="button" href="/bug/show/{$ITEM_NEXT_ID}" title="Следующий элемент">></a>
                                {else}
                                    <span class="button ui-button-disabled ui-state-disabled" title="Следующий элемент">></span>
                                {/if}
                                <a class="button" href="/bug/add/" title="Создать элемент">*</a>
                                <span class="button" title="Удалить элемент">x</span>
                            </div>
							{if $ERROR neq ""}<strong class="error" id="error">{$ERROR}</strong>{/if}
							<dl>
								<dt>№</dt><dd>{$BUG.ID}</dd>
								<dt><label for="title">Заголовок</label></dt><dd><input type="text" id="title" name="title" value="{$BUG.Title}" disabled="disabled" /></dd>
								<dt>Проект</dt><dd>{$BUG.ProjectName}</dd>
								<dt>Владелец</dt><dd><a href="/profile/show/{$BUG.UserID}/">{$BUG.NickName}</a></dd>
								<dt>Дата создания</dt><dd>{$BUG.CreateDateTime}</dd>
								<dt>Тип</dt><dd>{$BUG.KindN}</dd>
								<dt><label style="padding: 2px;">Статус</label></dt>
								{if $CAN_EDIT_STATUS neq true}
								<dd class="{bug_type value=$BUG.Status}">
									<div class="{bug_type value=$BUG.Status}" style="padding: 2px;">
									{$BUG.StatusN}
									</div>
								</dd>
								{else}
								<dd>
									<div class="{bug_type value=$BUG.Status}" style="padding: 2px;">
									<select name="state">
										{html_options options=$STATUSES.values selected=$STATUSES.selected}
									</select>
									</div>
								</dd>
								{/if}
								<dt>Приоритет</dt><dd>{$BUG.PriorityLevelN}</dd>
								<dt><label for="assigned_to">Назначено</label></dt>
								<dd>									
									<select id="assigned_to" name="assigned_to" disabled="disabled" >
										<option value="">-</option>
										{html_options options=$USERS_ASSIGN_TO selected=$BUG.AssignedTo}
									</select>
								</dd>
								{if $BUG.Kind eq Defect}
								<dt class="for_defect"><label for="error_type">Вид ошибки</label></dt>
								<dd class="for_defect">									
									<select id="error_type" name="error_type" disabled="disabled" >
										{html_options options=$DEFECT_TYPE.values selected=$DEFECT_TYPE.selected}
									</select>
								</dd>
								{/if}
								<dt class="for_defect"><label for="descr">Описание</label></dt>
								<dd class="for_defect"><textarea id="descr" name="descr" rows="10" cols="20" disabled="disabled" >{$BUG.Description}</textarea></dd>
								{if $BUG.Kind eq Defect}
								<dt class="for_defect"><label for="steps">Действия, которые привели к ошибке</label></dt>
								<dd class="for_defect"><textarea id="steps" name="steps" rows="10" cols="20" disabled="disabled" >{$BUG.StepsText}</textarea></dd>
								{/if}
								<dt>&nbsp;</dt>
								<dd class="subm"><input type="submit" name="cnange_state" value="Сохранить изменения" {if $CAN_EDIT_STATUS neq true}disabled="disabled"{/if} /></dd>						
							</dl>
						</div>
					</form>
				{/if}
			</div>
			<div id="comments">
				{if $ERROR neq ""}
					<strong class="error" id="error">{$ERROR}</strong>
				{/if}
				<div class="groupier">
					<strong>Комментарии</strong>
					{$COMMENTS_PAGINATOR}
				</div>
			{if $COMMENTS neq NULL}
			<form action="#" class="reports_form" method="post">
				<table class="comments">
					<col width="25" valign="top" />
					<thead>
						<tr>
							<th><input name="del_all" type="checkbox" title="" /></th>
							<th><a href="{$COMMENTS_ORDER.NickName.url}#comments" {if $COMMENTS_ORDER.NickName.order eq true}class="sort"{/if}>Пользователь</a></th>
							<th><a href="{$COMMENTS_ORDER.Comment.url}#comments" {if $COMMENTS_ORDER.Comment.order eq true}class="sort"{/if}>Комментарий</a></th>
							<th class="date"><a href="{$COMMENTS_ORDER.Time.url}#comments" {if $COMMENTS_ORDER.Time.order eq true}class="sort"{/if}>Дата</a></th>
						</tr>
					</thead>
					<tbody>
				{foreach name=bugComments from=$COMMENTS item=element} {* Комментарии отчёта *}
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
					<input type="submit" value="Удалить выделенные" title="Удалить выделенные" name="del" id="del" />
				</div>
			</form>
			{else}
				<strong>Комментариев нет</strong>
			{/if}
				<div>
					<form action="#comments" method="post">
						<div>
							<dl>
								<dd style="padding-right:4px">
									<textarea style="width: 100%; margin: 15px 0pt;" rows="7" cols="100" name="comment"> </textarea>

								</dd>
								<dd class="subm">
									<input type="submit" name="sendComment" value="Оставить комментарий"/>
								</dd>
							</dl>
						</div>
					</form>
				</div>
			</div>

			<div id="history">
				<div class="groupier">
					<strong>История изменений</strong>
				</div>
			{if $HISTORY neq NULL}
				<table class="comments">
					<thead>
						<tr><th>Пользователь</th><th>Действие</th><th class="date">Дата</th></tr>
					</thead>
					<tbody>
						{foreach name=bugHistory from=$HISTORY item=element} {* Комментарии отчёта *}
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