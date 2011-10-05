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
<script type="text/javascript">
		/* <![CDATA[ */
			$(document).ready(function() {
				$("#submit_item").click(function(){
					location.replace("/bug/show/"+$("#item").val()+"/");
					alert("/bug/show/"+$("#item").val()+"/");
					return false;
				});
			});
		/* ]]>*/
</script>
<div id="goTask">
	<form action="#">
		<div>
			<label>� </label><input type="text" maxlength="10" name="item" id="item" />

			<input type="submit" value="OK" id="submit_item" />
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
		<li id="cur"><a href="/my/bugs/">��� ������</a></li>
		<li><a href="/my/project/bugs/">������ �������</a></li>
		<li><a href="/my/projects/">��� �������</a></li>
		<li><a href="subscribes.html">��� ������</a></li>
		<li><a href="find.html">�����</a></li>
		<li><a href="/profile/show/">�������</a></li>
	</ul>
</div>
{/block}