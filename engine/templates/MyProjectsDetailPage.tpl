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
				<dd><a href="/#">{$Project.NickName}</a></dd>				
				<dt>��������</dt> 
				<dd>{$Project.Description}</dd> 
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
			<form action="#" class="reports_form"> 
				<table class="projects_table"> 
					<col width="23" /> 
					<thead> <tr> 
						<th><input name="del" type="checkbox" /></th> 
						<th><a href="#">������������</a></th> 
						<th><a href="#">���������� �������</a></th> 
						<th><a href="#">���������� ������������</a></th> 
						<th colspan="5"><a href="#">����������� �������</a></th> 
					</tr> 
					</thead> 
					<tbody> 
						<tr class="odd"> 
						<td><input name="delId" type="checkbox" /></td> 
						<td><strong><a href="#">Sudo777</a></strong> 
						</td> 
						<td>456</td> 
						<td><a href="#">213</a></td> 
						<td class="new">223</td><td class="confirmed">316</td><td class="assigned">90</td><td class="solved">101</td><td class="closed">72</td> 
						</tr> 
						<tr class="even"> 
						<td><input name="delId" type="checkbox" /></td> 
						<td><a href="#">EntityFX</a></td> 
						<td>123</td> 
						<td><a href="#">213</a></td> 
						<td class="new">223</td><td class="confirmed">316</td><td class="assigned">90</td><td class="solved">101</td><td class="closed">72</td> 
						</tr> 
						<tr class="odd"> 
						<td><input name="delId" type="checkbox" /></td> 
						<td><a href="#">Flood</a></td> 
						<td>789<br /> 
						</td> 
						<td><a href="#">213</a></td> 
						<td class="new">223</td><td class="confirmed">316</td><td class="assigned">90</td><td class="solved">101</td><td class="closed">72</td> 
						</tr> 
						<tr class="even"> 
						<td><input name="delId" type="checkbox" /></td> 
						<td><a href="#">EntityFX</a></td> 
						<td>123<br /> 
						</td> 
						<td><a href="#">213</a></td> 
						<td class="new">223</td><td class="confirmed">316</td><td class="assigned">90</td><td class="solved">101</td><td class="closed">72</td> 
						</tr> 
						<tr class="odd"> 
						<td><input name="delId" type="checkbox" /></td> 
						<td><a href="#">Ignatyy</a></td> 
						<td>123</td> 
						<td><a href="#">213</a></td> 
						<td class="new">223</td><td class="confirmed">316</td><td class="assigned">90</td><td class="solved">101</td><td class="closed">72</td> 
						</tr> 
					</tbody> 
				</table> 
				<div class="groupier"> 
					<input value="������� ���������� �����������" name="delBtn" type="button" /> 
				</div> 
			</form> 
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