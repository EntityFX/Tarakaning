{extends file="main.base.tpl"}
{block name=script}
		<script type="text/javascript">
		/* <![CDATA[ */
			$(document).ready(function() {
				$("#tabs").tabs();
				$("input:button, input:submit, button, .groupier a, .groupier li span, #exit").button();	
				$('.reports_form').checkboxes({
					onText: "*",
					offText: "",
					titleOn: "�������� ���",
					titleOff: "������ ��"
				});
				
				$("#project_id").change(function(){
					var form=$("#selectProjectForm");
					form[0].submit();
				});
			});
		/* ]]>*/
		</script>
{/block}
{block name=info}
<div id="goTask">
	<form action="#">
		<div>
			<label>� </label><input type="text" maxlength="10" name="bug" />

			<input type="submit" value="OK" />
		</div>
	</form>
</div>
<div id="account_panel">
	<strong>{$LOGIN}</strong><br/>
	<span>{$FULLNAME}</span><br />
	<span>�����: <span style="color: #aaa">{$TIME}</span></span><br />
	<a href="/logout/" id="exit" >�����</a>
</div>
{/block}
{block name=menu}
<div id="main_menu">
	<ul>
		<li id="cur"><a href="my_reports_with_checkboxes.html">��� ������</a></li>
		<li><a href="reports_with_checkboxes.html">������ �������</a></li>

		<li><a href="projects.html">��� �������</a></li>
		<li><a href="subscribes.html">��� ������</a></li>
		<li><a href="find.html">�����</a></li>
		<li><a href="user_info.html">�������</a></li>
	</ul>
</div>
{/block}