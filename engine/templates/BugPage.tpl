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
	{if $ERROR neq ""}
        <div class="alert alert-error" id="error">
            <a class="close" data-dismiss="alert" href="#">&times;</a>
            {$ERROR}
        </div>
    {/if}
	<ul class="nav nav-tabs" id="item-tab">
    	<li class="active"><a href="#description" data-toggle="tab">Описание</a></li>
        <li><a href="#comments" data-toggle="tab">Комментарии<span class="label">{$COMMENT_COUNT}</span></a></li>
        <li><a href="#history" data-toggle="tab">История изменений</a></li>
        <li><a href="#attachments" data-toggle="tab">Файлы</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="description">  
			{if $CAN_EDIT_DATA eq TRUE}
			<div class="btn-toolbar">
				<div class="btn-group">
					<a class="btn" href="/bug/show/{$ITEM_PREV_ID}" rel="tooltip" title="К предыдущему элементу"><i class="icon-arrow-left"></i></a>
					<a class="btn" href="/bug/show/{$ITEM_NEXT_ID}" rel="tooltip" title="К следующему элементу"><i class="icon-arrow-right"></i></a>
				</div>
				<div class="btn-group">
					<a class="btn btn-danger" href="/bug/add/"><i class="icon-trash icon-white"></i></a>
					<a class="btn btn-primary" href="#"><i class="icon-asterisk icon-white"></i></a>
				</div>
			</div>
			<form class="form-horizontal" method="post" action="#">
				<fieldset>
					<legend>{$BUG.KindN} № {$BUG.ID}</legend>
						<div class="control-group">
							<label class="control-label" for="title">Заголовок</label>
							<div class="controls">
								<input type="text" class="input-xlarge" id="title" name="title" value="{$BUG.Title}">
							</div>
							</div>
							<div class="control-group">
								<label class="control-label">Проект</label>
								<div class="controls">
									<label>
										<span>{$BUG.ProjectName}</span>
									</label>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Владелец</label>
								<div class="controls">
									<label>
										<a href="/profile/show/{$BUG.UserID}/">{$BUG.NickName}</a>
									</label>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Дата создания</label>
								<div class="controls">
									<label>
										<time>{$BUG.CreateDateTime}</time>
									</label>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">Тип</label>
								<div class="controls">
									<label>
										<span>{$BUG.KindN}</span>
									</label>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="state">Статус</label>
								<div class="controls">
									<select id="state" name="state">
										{html_options options=$STATUSES.values selected=$STATUSES.selected}
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="priority">Приоритет</label>
								<div class="controls">
									<select name="priority" id="priority">
										{html_options options=$PRIORITY_LEVEL.values selected=$PRIORITY_LEVEL.selected}
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="assigned_to">Назначено</label>
								<div class="controls">
									<select name="assigned_to" id="assigned_to">
										<option value="">-</option>
										{html_options options=$USERS_ASSIGN_TO selected=$BUG.AssignedTo}
									</select>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="hour_req">Требуется на работу</label>
								<div class="controls">
									<div class="input-append">
										<input class="input-mini align-right" type="text" maxlength="5" value="{$BUG.HoursRequired}" name="hour_req" id="hour_req"><span class="add-on">ч</span>
									</div>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="hour_req">Затрачено времени</label>
								<div class="controls">
									<span class="help-inline">{$BUG.HoursFact} +&nbsp;</span>
									<div class="input-append">
										<input class="input-mini align-right" type="text" maxlength="5" value="0" name="add_hour_fact" id="add_hour_fact"><span class="add-on">ч</span>
									</div>
								</div>
							</div>
							{if $BUG.Kind eq Defect}
							<div class="control-group">
								<label class="control-label" for="error_type">Вид ошибки</label>
								<div class="controls">
									<select name="error_type" id="error_type">
										{html_options options=$DEFECT_TYPE.values selected=$DEFECT_TYPE.selected}
									</select>
								</div>
							</div>
							{/if}
							<div class="control-group">
								<label class="control-label" for="descr">Описание</label>
								<div class="controls">
									<textarea id="descr" name="descr"  class="input-xxlarge" rows="9">{$BUG.Description}</textarea>
								</div>
							</div>
							{if $BUG.Kind eq Defect}
							<div class="control-group">
								<label class="control-label" for="steps">Шаги</label>
								<div class="controls">
									<textarea id="steps" name="steps" class="input-xxlarge" rows="9">{$BUG.StepsText}</textarea>
								</div>
							</div>
							{/if}
							<div class="form-actions">
								<input type="submit" class="btn btn-primary" name="cnange_state" value="Сохранить изменения" />
							</div>
				</fieldset>
			</form>
			{else}
			<div class="btn-toolbar">
				<div class="btn-group">
					{if $ITEM_PREV_ID neq NULL}
						<a class="btn" href="/bug/show/{$ITEM_PREV_ID}" rel="tooltip" title="К предыдущему элементу"><i class="icon-arrow-left"></i></a>
					{else}
						<a class="btn" href="#" rel="tooltip" title="К предыдущему элементу"><i class="icon-arrow-left"></i></a>
					{/if}
					{if $ITEM_NEXT_ID neq NULL}
						<a class="btn" href="/bug/show/{$ITEM_NEXT_ID}" rel="tooltip" title="К следующему элементу"><i class="icon-arrow-right"></i></a>
					{else}
						<a class="btn" href="#" rel="tooltip" title="К следующему элементу"><i class="icon-arrow-right"></i></a>
					{/if}
				</div>
				<div class="btn-group">
					<a class="btn btn-danger" href="/bug/add/"><i class="icon-trash icon-white"></i></a>
					<a class="btn btn-primary" href="#"><i class="icon-asterisk icon-white"></i></a>
				</div>
			</div>
			<form class="form-horizontal">
				<fieldset>
				<legend>{$BUG.KindN} № {$BUG.ID}</legend>
					<div class="control-group">
						<label class="control-label" for="title">Заголовок</label>
						<div class="controls">
							<input type="text" class="input-xlarge" id="title" name="title" value="{$BUG.Title}" disabled="disabled" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Проект</label>
						<div class="controls">
							<label>
								<span>{$BUG.ProjectName}</span>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Владелец</label>
						<div class="controls">
							<label>
								<a href="/profile/show/{$BUG.UserID}/">{$BUG.NickName}</a>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Дата создания</label>
						<div class="controls">
							<label>
								<time>{$BUG.CreateDateTime}</time>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Тип</label>
						<div class="controls">
							<label>
								<span>{$BUG.KindN}</span>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="state">Статус</label>
						<div class="controls">
							{if $CAN_EDIT_STATUS neq true}
								<label>
									<span>{$BUG.StatusN}</span>
								</label>
							{else}
							<select id="state" name="state">
								{html_options options=$STATUSES.values selected=$STATUSES.selected}
							</select>
							{/if}
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="priority">Приоритет</label>
						<div class="controls">
							<select name="priority" id="priority" disabled="disabled" >
								{html_options options=$PRIORITY_LEVEL.values selected=$PRIORITY_LEVEL.selected}
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="assigned_to">Назначено</label>
						<div class="controls">
							<label>
								<span>{$BUG.PriorityLevelN}</span>
							</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="hour_req">Требуется на работу</label>
						<div class="controls">
							<div class="input-append">
								<input class="input-mini align-right" type="text" maxlength="5" disabled="disabled" value="{$BUG.HoursRequired}" name="hour_req" id="hour_req"><span class="add-on">ч</span>
							</div>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="hour_req">Затрачено времени</label>
						<div class="controls">
							<span class="help-inline">{$BUG.HoursFact} +&nbsp;</span>
							<div class="input-append">
								<input class="input-mini align-right" type="text" maxlength="5" disabled="disabled" value="0" name="add_hour_fact" id="add_hour_fact"><span class="add-on">ч</span>
							</div>
						</div>
					</div>
					{if $BUG.Kind eq Defect}
					<div class="control-group">
						<label class="control-label" for="error_type">Вид ошибки</label>
						<div class="controls">
							<select name="error_type" id="error_type" disabled="disabled">
								{html_options options=$DEFECT_TYPE.values selected=$DEFECT_TYPE.selected}
							</select>
						</div>
					</div>
					{/if}
					<div class="control-group">
						<label class="control-label" for="descr">Описание</label>
						<div class="controls">
							<textarea id="descr" name="descr" disabled="disabled" class="input-xxlarge" rows="9">{$BUG.Description}</textarea>
						</div>
					</div>
					{if $BUG.Kind eq Defect}
					<div class="control-group">
						<label class="control-label" for="steps">Шаги</label>
						<div class="controls">
							<textarea id="steps" name="steps" disabled="disabled" class="input-xxlarge" rows="9">{$BUG.StepsText}</textarea>
						</div>
					</div>
					{/if}
					<div class="form-actions">
						<input type="submit" class="btn btn-primary" name="cnange_state" value="Сохранить изменения" {if $CAN_EDIT_STATUS neq true}disabled="disabled"{/if} />
					</div>
				</fieldset>
			</form>
			{/if}
        </div>
		<div class="tab-pane" id="comments">
			<div class="span6">
               		<div class="row-fluid tarakaning-toolbar">
                    	<div class="btn-toolbar">
                        	<div class="pagination pagination-right">
                        		{$COMMENTS_PAGINATOR}
                        	</div>
                        </div>
                    </div>
                    {if $COMMENTS neq NULL}
                    	{foreach name=bugComments from=$COMMENTS item=element} {* Комментарии элемента *}
                    		<blockquote>
                            	<button class="close" data-dismiss="alert" type="button">&times;</button>
                                <p>{$element.Comment}</p>
                                <small><a href="/profile/show/{$element.UserID}/">{$element.NickName}</a></small><time>{$element.Time}</time>
                        	</blockquote>
                    	{/foreach}                       
                    {else}
                    	<strong>Комментариев нет</strong>
                    {/if}
			</div>
            <div class="span6">
                	<form class="well" action="#comments" method="post">
                    	<label class="control-label" for="comment">Комментарий</label>
                        <textarea id="comment" name="comment" rows="5"></textarea>
                        <input class="btn btn-primary" type="submit" name="sendComment" value="Отправить">
                    </form>
            </div>
		</div>
		<div class="tab-pane" id="history">
				{if $HISTORY neq NULL}
					<table class="table table-bordered table-striped">
						<thead>
							<tr><th>Пользователь</th><th>Действие</th><th class="date">Дата</th></tr>
						</thead>
						<tbody>
							{foreach name=bugHistory from=$HISTORY item=element} {* Комментарии отчёта *}
							<tr class="odd"><td><a href="#">{$element.USER_ID}</a></td><td class="left">{$element.DESCR}</td><td>{$element.OLD_TM}</td></tr>
							{/foreach}
						</tbody>
					</table>
				{/if}
		</div>
        <div class="tab-pane" id="attachments">
		</div>
	</div>
{/block}