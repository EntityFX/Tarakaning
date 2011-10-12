{extends file="info.base.tpl"}
{block name=body}
<div id="content_body"> 
	<div id="tabs"> 
		<ul> 
			<li><a href="#about"><span>Описание</span></a></li> 
			<li><a href="#users"><span>Участники</span></a></li> 
			<li><a href="#requests"><span>Заявки</span></a></li>			
		</ul> 
		<div id="about" class="info_div">			
			<dl> 
				<dt>Название</dt> 
				<dd>{$Project.Name}</dd> 
				<dt>Автор</dt> 
				<dd><a href="/profile/show/{$Project.OwnerID}/">{$Project.NickName}</a></dd>				
				<dt>Описание</dt> 
				<dd>{$Project.Description}&nbsp;</dd> 
			</dl> 
			<form action="/my/project/edit/" method="post">
				<div>
					<input type="submit" value="Редактировать проект" />
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
						<th><a href="{$MY_PROJECT_ORDERER.NickName.url}#users" {if $MY_PROJECT_ORDERER.NickName.order eq true}class="sort"{/if}>Пользователь</a></th> 
						<th><a href="{$MY_PROJECT_ORDERER.CountCreated.url}#users" {if $MY_PROJECT_ORDERER.CountCreated.order eq true}class="sort"{/if}>Создано</a></th> 
						<th><a href="#">Количество комментариев</a></th> 
						<th colspan="5"><a href="{$MY_PROJECT_ORDERER.CountErrors.url}#users" {if $MY_PROJECT_ORDERER.CountErrors.order eq true}class="sort"{/if}>Назначенные</a></th> 
					</tr> 
					</thead> 
					<tbody> 
					{foreach name=projectUsers from=$PROJECT_USERS item=element} {* Выводит мои проекты*}
						<tr class="{if $smarty.foreach.projectUsers.index % 2 == 0}odd{else}even{/if}"> 
							<td><input name="delId" type="checkbox" /></td> 
							<td>{if $element.Owner eq 1}<strong><a href="/profile/show/{$element.UserID}/">{$element.NickName}</a></strong>&nbsp;<sup style="font-size: 10px; color: red;">(владелец)</sup>{else}<a href="/profile/show/{$element.UserID}/">{$element.NickName}</a>{/if}</td> 
							<td>{$element.CountCreated}</td> 
							<td><a href="#">213</a></td> 
							<td class="new">{$element.NEW}</td><td class="confirmed">{$element.IDENTIFIED}</td><td class="assigned">{$element.ASSESSED}</td><td class="solved">{$element.RESOLVED}</td><td class="closed">{$element.CLOSED}</td>
						</tr> 
					{/foreach}
					</tbody> 
				</table> 
				<div class="groupier"> 
					<input value="Удалить выделенных подписчиков" name="delBtn" type="button" /> 
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
				  <th><a href="#">Пользователь</a></th> 
				  <th><a href="#">Действие</a></th> 
				</tr> 
				</thead> <tbody> 
				  <tr class="odd"> 
					<td><input name="delId" type="checkbox" /></td> 
					<td><a href="#">EntityFX</a></td> 
					<td><button type="button">Подтвердить</button><button type="button">Отменить</button></td> 
				  </tr> 
				  <tr class="even"> 
					<td><input name="delId" type="checkbox" /></td> 
					<td><a href="#">Flood</a></td> 
					<td><button type="button">Подтвердить</button><button type="button">Отменить</button></td> 
				  </tr> 
				  <tr class="odd"> 
					<td><input name="delId" type="checkbox" /></td> 
					<td>Тити</td> 
					<td><button type="button">Подтвердить</button><button type="button">Отменить</button></td> 
				  </tr> 
				  <tr class="even"> 
					<td><input name="delId" type="checkbox" /></td> 
					<td><a href="#">Sudo777</a><br /> 
					</td> 
					<td><button type="button">Подтвердить</button><button type="button">Отменить</button></td> 
				  </tr> 
				  <tr class="odd"> 
					<td><input name="delId" type="checkbox" /></td> 
					<td><a href="#">Sudo777</a></td> 
					<td><button type="button">Подтвердить</button><button type="button">Отменить</button></td> 
				  </tr> 
				</tbody> 
			  </table> 
				<div class="groupier"> 
					<input value="Подтвердить заявки" name="deSubscr" type="button" /> 
					<input value="Удалить заявки" name="delBtn" type="button" /> 
				</div> 
			</form> 
		</div> 
    </div> 
</div>
{/block}