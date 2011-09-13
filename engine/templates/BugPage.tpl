{extends file="info.base.tpl"}
{block name=body}
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
						<tr><td><strong>��������� ������</strong></td><td><strong>{$BUG.Title}</strong></td></tr>
						<tr><td><b>������</b></td><td class="new">����� (<a href="#">���</a>)</td></tr>
						<tr><td><b>��������</b></td><td><a href="#">{$BUG.NickName}</a></td></tr>
						<tr><td><b>���������</b></td><td>{$BUG.PriorityLevel}</td></tr>
						<tr><td><b>������</b></td><td>{$BUG.ProjectName}</td></tr>
						<tr><td><b>��� ������</b></td><td>{$BUG.ErrorType}</td></tr>
						<tr><td><b>���� ��������</b></td><td>{$BUG.Time}</td></tr>
						<tr><td><b>��������</b></td><td>{$BUG.Description}</td></tr>
						<tr>
							<td><b>��������, ������� ������� � ������</b></td><td>
							{$BUG.StepsText}
							</td>
						</tr>
					</tbody>

				</table>
			</div>
			<div id="comments">
				<div class="groupier">
					<strong>�����������</strong>
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
						<tr><th>������������</th><th>�����������</th><th class="date">����</th></tr>
					</thead>

					<tbody>
						<tr class="odd"><td><a href="#">EntityFX</a></td><td class="left">� ��� �� ������������� � �������� ������� �������. ����� �����, ���� �� ���������. ������ ���� ������, ���� �� �������� � �������� �������. � �� ������ �����.</td><td>6 ������� 2007 15:47</td></tr>
						<tr class="even"><td><a href="#">Sudo777</a> (<a href="#">X</a>)</td><td class="left">� ��� ����, ��� ��� � ����. ����� ������. ��� ���������� � ������ ����� ������?</td><td>6 ������� 2007 17:34</td></tr>
						<tr class="odd"><td><a href="#">BrainUnlocker</a> (<a href="#">X</a>)</td><td class="left">� ���� ���������� ������� ����� ��� �������, ��� ����� �������</td><td>6 ������� 2007 18:03</td></tr>

						<tr class="even"><td><a href="#">Sudo777</a> (<a href="#">X</a>)</td><td class="left">��������� ������������ ���������� MicrovaweKeeper2000. �� ������ ��������, ��� ������ ����, ������ ��������� �� ������ ������ ������, �� ��� �������� ������������ ������ ������ �������������, �������������� ��� ������������������� �������� ������ � ����� �������.</td><td>6 ������� 2007 19:00</td></tr>
						<tr class="odd"><td><a href="#">EntityFX</a> (<a href="#">X</a>)</td><td class="left">������������ �� ����� ����, ��� ������� ���� ����������� ����</td><td>6 ������� 2007 19:01</td></tr>

					</tbody>
				</table>
				<div>
					<form action="#">
						<div>
							<dl>
								<dd style="padding-right:4px">
									<textarea style="width: 100%; margin: 15px 0pt;" rows="7" cols="100" name="comment"> </textarea>

								</dd>
								<dd class="subm">
									<input type="submit" value="�������� �����������"/>
								</dd>
							</dl>
						</div>
					</form>
				</div>
			</div>

			<div id="history">
				<div class="groupier">
					<strong>������� ���������</strong>
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
						<tr><th>������������</th><th>��������</th><th class="date">����</th></tr>
					</thead>
					<tbody>
						<tr class="odd"><td><a href="#">BrainUnlocker</a></td><td class="left">������ ������ � <strong>��������� � �������������</strong> �� <strong>��������� (<a href="#">Sudo777</a>)�</strong></td><td>6 ������� 2007 20:26</td></tr>

						<tr class="even"><td><a href="#">EntityFX</a></td><td class="left">������ ������ � <strong>������</strong> �� <strong>��������� � �������������</strong></td><td>6 ������� 2007 19:00</td></tr>
						<tr class="odd"><td><a href="#">EntityFX</a> (<a href="#">X</a>)</td><td class="left">������ ����� �� ������</td><td>5 ������� 2007 19:01</td></tr>

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