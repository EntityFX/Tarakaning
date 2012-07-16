{extends file="MainBasePage.base.tpl"}

{block name=script}
		<script type="text/javascript">
		/* <![CDATA[ */
			$(document).ready(function() {
                $("#item").focus();

				$("#submit_item").click(function(){
					location.replace("/bug/show/"+$("#item").val()+"/");
					return false;
				});
				
				{if $PROJECT_SELECTION_FLAG eq true}
					$("#project_id").change(function(){
						$("#selectProjectForm").submit();
					});
				{/if}
				{$smarty.block.child}
			});
		/* ]]>*/
		</script>
{/block}

{block name=info}
    <div id="project-panel" class="btn-toolbar well well-small">
        <div class="btn-group">
            <form class="form-inline" id="selectProjectForm" action="#">
                <label for="project_id">�������� ������</label>
                <select id="project_id" name="project_id" {if $PROJECT_SELECTION_FLAG neq true}disabled="disabled"{/if}>
                {if $PROJECTS.PROJECTS_LIST neq NULL}
					{html_options options=$PROJECTS.PROJECTS_LIST selected=$PROJECTS.selected}
				{/if}
                </select>
            </form>
        </div>
        <div class="divider"></div>
        {if $PROJECTS.PROJECTS_LIST neq NULL}
        <a class="btn btn-primary" href="/bug/add/" title="������� ����� �����/������">������� �������</a>
        {/if}
        <a class="btn btn-primary" href="/my/project/new/" title="������� ����� ������">������� ������</a>
        <div class="divider"></div>
        <div class="btn-group">
            <form class="form-inline" action="#">
                <label for="item">������� � �</label>
                <input class="input-mini" id="item" type="text" name="item" maxlength="8">
                <input type="submit" value="OK" id="submit_item" class="btn" />
            </form>
        </div>
    </div>
{/block}

{block name=menu}
    <div class="navbar-inner">
        <div class="container-fluid">
            <a class="brand" href="#">Tarakaning</a>
            <ul class="nav">
                <li><a href="/my/projects/">�������</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">��������
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="/my/bugs/">��� ��������</a></li>
                        <li><a href="/my/project/bugs/">�������� �������</a></li>
                    </ul>
                </li>
                <li><a href="/requests/">������</a></li>
                <li><a href="/search/">�����</a></li>
            </ul>
            <div class="btn-group pull-right">
                <a href="#" data-toggle="dropdown" class="btn dropdown-toggle">
                    <i class="icon-user"></i>
                    {$LOGIN}
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="/profile/edit/">
                            <i class="icon-user"></i>
                            ���������
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="/logout/">
                            <i class="icon-off"></i>
                            �����
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
{/block}