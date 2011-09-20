{extends file="info.base.tpl"}
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
				<table id="report">
					<col width="250" valign="top" />
					<tbody>
						<tr><td><strong>№</strong></td><td><strong>{$BUG.ID}</strong></td></tr>
						<tr><td><strong>Заголовок отчёта</strong></td><td><strong>{$BUG.Title}</strong></td></tr>
						<tr><td><b>Статус</b></td><td class="{bug_type value=$BUG.Status}">{$BUG.StatusN} (<a href="#">ред</a>)</td></tr>
						<tr><td><b>Владелец</b></td><td><a href="#">{$BUG.NickName}</a></td></tr>
						<tr><td><b>Приоритет</b></td><td>{$BUG.PriorityLevel}</td></tr>
						<tr><td><b>Проект</b></td><td>{$BUG.ProjectName}</td></tr>
						<tr><td><b>Назначено</b></td><td>{if $BUG.AssignedTo neq null}<a href="/profile/show/{$element.AssignedTo}/">{$BUG.AssignedNickName}</a>{/if}</td></tr>
						<tr><td><b>Тип ошибки</b></td><td>{$BUG.ErrorType}</td></tr>
						<tr><td><b>Дата создания</b></td><td>{$BUG.Time}</td></tr>
						<tr><td><b>Описание</b></td><td>{$BUG.Description}</td></tr>
						<tr>
							<td><b>Действия, которые привели к ошибке</b></td><td>
							{$BUG.StepsText}
							</td>
						</tr>
					</tbody>

				</table>
			</div>
			<div id="comments">
				{if $ERROR neq ""}
					<strong class="error" id="error">{$ERROR}</strong>
				{/if}
				<div class="groupier">
					<strong>Комментарии</strong>
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
			{if $COMMENTS neq NULL}
				<table class="comments">
					<thead>
						<tr><th>Пользователь</th><th>Комментарий</th><th class="date">Дата</th></tr>
					</thead>
					<tbody>
				{foreach name=bugComments from=$COMMENTS item=element} {* Комментарии отчёта *}
					<tr class="{if $smarty.foreach.bugComments.index % 2 == 0}odd{else}even{/if}">
						<td><a href="/profile/show/{$element.UserID}/">{$element.NickName}</a> {if $element.UserID eq $USER_ID}(<a href="" class="strongest">X</a>){/if}</td>
						<td class="left">{$element.Comment}</td>
						<td>{$element.Time}</td>
					</tr>
				{/foreach}
					</tbody>
				</table>
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

				<table class="comments">
					<thead>
						<tr><th>Пользователь</th><th>Действие</th><th class="date">Дата</th></tr>
					</thead>
					<tbody>
						<tr class="odd"><td><a href="#">BrainUnlocker</a></td><td class="left">Изменён статус с <strong>“Принятый к рассмотрению”</strong> на <strong>“Назначен (<a href="#">Sudo777</a>)”</strong></td><td>6 февраля 2007 20:26</td></tr>

						<tr class="even"><td><a href="#">EntityFX</a></td><td class="left">Изменён статус с <strong>“Новый”</strong> на <strong>“Принятый к рассмотрению”</strong></td><td>6 февраля 2007 19:00</td></tr>
						<tr class="odd"><td><a href="#">EntityFX</a> (<a href="#">X</a>)</td><td class="left">Создан отчёт об ошибке</td><td>5 февраля 2007 19:01</td></tr>

					</tbody>
				</table>
				<div class="groupier">
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

			</div>
			<div id="attachments">
			</div>
		</div>
	</div>
{/block}