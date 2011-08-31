{extends file="info.base.tpl"}
{block name=body}
<div id="content_body">
	<div id="tabs">
		<ul>
			<li><a href="#my_project"><span>��� �������</span></a></li>
			<li><a href="#all_projects"><span>��� �������</span></a></li>			
		</ul>
		<div id="my_project">
			<div class="groupier">
				<a href="new_project.html">������� ����� ������</a>
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
			{if $MY_PROJECTS neq NULL}
			<form action="#" class="reports_form">
				<table class="projects_table">
					<col width="23" />
					<thead> 
						<tr>
						  <th><input name="del" type="checkbox" /></th>
	
						  <th><a href="#">������</a></th>
						  <th><a href="#">���������</a></th>
						  <th colspan="5"><a href="#">�������</a></th>
						  <th><a href="#">������</a></th>
						  <th><a href="#">����</a></th>
						</tr>
					</thead> 
					<tbody>
				{foreach from=$MY_PROJECTS item=element} {* ������� ��� �������*}
						<tr class="odd">
							<td><input name="delId" type="checkbox" /></td>
							<td><a href="my_project_properties.html">{$element.Name}</a><br />
							</td>
							<td>{$element.Description}</td>
							<td class="new">{$element.New}</td><td class="confirmed">{$element.Confirmed}</td><td class="assigned">{$element.Assigned}</td><td class="solved">{$element.Solved}</td><td class="closed">{$element.Closed}</td>
			
							<td><strong class="strongest">2</strong></td>
							<td>{$element.CreateDate}</td>
						</tr>
				{/foreach}
			  	</table>
				<div class="groupier">
					<input value="�������" name="delBtn" type="button" />
				</div>
			</form>
			{else}
				<span>�������� ���</span>
			{/if}
		</div>
		<div id="all_projects">
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
			  <table class="projects_table">
				<thead> 
					<tr>
						<th><a href="#">������</a></th>
						<th><a href="#">���������</a></th>
						<th><a href="#">��������</a></th>
						<th colspan="5"><a href="#">�������</a></th>

						<th><a href="#">������</a></th>
						<th><a href="#">����</a></th>
					</tr>
				</thead> 
				<tbody>
				  <tr class="odd">
					<td>��� ���������</td>
					<td>������������</td>

					<td><a href="#">Yeldy</a></td>
					<td class="new">23</td><td class="confirmed">36</td><td class="assigned">9</td><td class="solved">11</td><td class="closed">2</td>
					<td><strong class="strongest">2</strong></td>
					<td>6 ������� 2007 12:56</td>
				  </tr>

				  <tr class="even">
					<td>��� ������</td>
					<td>������ ������</td>
					<td><a href="#">Trisha</a></td>
					<td class="new">13</td><td class="confirmed">678</td><td class="assigned">98</td><td class="solved">1</td><td class="closed">27</td>

					<td><strong class="strongest">1</strong></td>
					<td>7 ������� 2011 07:48</td>
				  </tr>
				  <tr class="odd">
					<td>��� �����������</td>
					<td>SQL-Lover</td>
					<td><a href="#">EntityFX</a></td>

					<td class="new">36</td><td class="confirmed">32</td><td class="assigned">19</td><td class="solved">28</td><td class="closed">46</td>
					<td><strong class="strongest">6</strong></td>
					<td>23 ������� 1989 11:34</td>
				  </tr>
				  <tr class="even">

					<td>��� ����������</td>
					<td>������. ���-������</td>
					<td><a href="#">EntityFX</a></td>
					<td class="new">223</td><td class="confirmed">316</td><td class="assigned">90</td><td class="solved">101</td><td class="closed">72</td>
					<td><strong>0</strong><br />

					</td>
					<td>7 ���� 2008 22:37</td>
				  </tr>
				  <tr class="odd">
					<td><a href="my_project_properties.html">��� �� </a></td>
					<td>����� ����</td>
					<td><a href="#">Sudo777</a></td>

					<td class="new">53</td><td class="confirmed">146</td><td class="assigned">34</td><td class="solved">45</td><td class="closed">11</td>
					<td><strong class="strongest">7</strong></td>
					<td>7 ���� 2008 22:37</td>
				  </tr>
		</div>
	</div>
</div>
{/block}