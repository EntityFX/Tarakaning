{extends file="InfoBasePage.base.tpl"}

{block name=script}
{literal}
    $('.reports_form').checkboxes({titleOn: "�������� ��", titleOff: "����� �������"});
    $('#del').click(function(){
    return confirm('�� ������������� ������� ������� �������? ��� ������� � �������� ���� �����, ������������, � ����� �����������');
    });

    $("#item_kind, #project_id").change(function(){
    $("#selectProjectForm").submit();
    });
{/literal}
{/block}

{block name=body}
<ul class="nav nav-tabs" id="item-tab">
    <li class="active"><a href="#my-projects" data-toggle="tab">��� �������</a></li>
    <li><a href="#all-projects" data-toggle="tab">��������� � ��������</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="my-projects">  
        {if $MY_PROJECTS neq NULL}
            <div class="row-fluid tarakaning-toolbar">
                <div class="btn-toolbar">
                    <div class="pagination pagination-right">
                        {$MY_PROJECTS_PAGINATOR}
                    </div>
                </div>
            </div>
            <form action="#" class="reports_form" method="post">
                <table class="table table-bordered table-striped">
                    <col width="23" />
                    <thead> 
                        <tr>
                            <th><input name="del" type="checkbox" /></th>
                            <th><a href="{$MY_PROJECTS_ORDERER.ProjectName.url}" {if $MY_PROJECTS_ORDERER.ProjectName.order eq true}class="sort"{/if}>������</a></th>
                            <th><a href="{$MY_PROJECTS_ORDERER.Description.url}" {if $MY_PROJECTS_ORDERER.Description.order eq true}class="sort"{/if}>���������</a></th>
                            <th colspan="5">���������</th>
                            <th><a href="{$MY_PROJECTS_ORDERER.CountSubscribeRequests.url}" {if $MY_PROJECTS_ORDERER.CountSubscribeRequests.order eq true}class="sort"{/if}>������</a></th>
                            <th><a href="{$MY_PROJECTS_ORDERER.CountUsers.url}" {if $MY_PROJECTS_ORDERER.CountUsers.order eq true}class="sort"{/if}>����������</a></th>
                            <th><a href="{$MY_PROJECTS_ORDERER.CreateDateTime.url}" {if $MY_PROJECTS_ORDERER.CreateDateTime.order eq true}class="sort"{/if}>���� ��������</a></th>
                        </tr>
                    </thead> 
                    <tbody>
                        {foreach name=myProjects from=$MY_PROJECTS item=element} {* ������� ��� �������*}
                            <tr>
                                <td><input name="del_i[{$element.ProjectID}]" type="checkbox" /></td>
                                <td><a href="/my/project/show/{$element.ProjectID}/">{$element.ProjectName}</a><br />
                                </td>
                                <td>{$element.Description}</td>
                                <td class="item-state-new">{$element.NEW}</td><td class="item-state-assesed">{$element.IDENTIFIED}</td><td class="item-state-inprocess">{$element.ASSESSED}</td><td class="item-state-solved">{$element.RESOLVED}</td><td class="closed">{$element.CLOSED}</td>
                                <td><strong {if $element.CountSubscribeRequests neq 0}class="strongest"{/if}>{$element.CountSubscribeRequests}</strong></td>
                                <td>{$element.CountUsers}</td>
                                <td>{$element.CreateDateTime}</td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
                <div class="btn-toolbar">
                    <input class="btn btn-danger" type="submit" id="del" name="del" title="������� ����������" value="������� ����������" />
                </div>
            </form>
        {else}
            <span>�������� ���</span>
        {/if}
    </div>
    <div class="tab-pane" id="all-projects">
        {if $PROJECTS_WITHOUT_ME neq NULL}
            <div class="pagination pagination-right">
                {$MEMBER_PROJECTS_PAGINATOR}
            </div>
            <table class="projects_table">
                <thead> 
                    <tr>
                        <th><a href="{$MEMBER_PROJECTS_ORDERER.ProjectName.url}#all_projects" {if $MEMBER_PROJECTS_ORDERER.ProjectName.order eq true}class="sort"{/if}>������</a></th>
                        <th><a href="{$MEMBER_PROJECTS_ORDERER.Description.url}#all_projects" {if $MEMBER_PROJECTS_ORDERER.Description.order eq true}class="sort"{/if}>��������</a></th>
                        <th><a href="{$MEMBER_PROJECTS_ORDERER.OwnerNickName.url}#all_projects" {if $MEMBER_PROJECTS_ORDERER.OwnerNickName.order eq true}class="sort"{/if}>��������</a></th>
                        <th colspan="5">�������</th>
                        <th><a href="{$MEMBER_PROJECTS_ORDERER.CountUsers.url}#all_projects" {if $MEMBER_PROJECTS_ORDERER.CountUsers.order eq true}class="sort"{/if}>����������</a></th>   
                        <th><a href="{$MEMBER_PROJECTS_ORDERER.CreateDateTime.url}#all_projects" {if $MEMBER_PROJECTS_ORDERER.CreateDateTime.order eq true}class="sort"{/if}>����</a></th>
                    </tr>
                </thead> 
                <tbody>
                    {foreach name=notMyProjects from=$PROJECTS_WITHOUT_ME item=element} {* ������� ��� �������*}
                        <tr>
                            <td><a href="/my/project/show/{$element.ProjectID}/">{$element.ProjectName}</a></td>
                            <td>{$element.Description}</td>
                            <td><a href="#">{$element.OwnerNickName}</a></td>
                            <td class="item-state-new">{$element.NEW}</td><td class="item-state-assesed">{$element.IDENTIFIED}</td><td class="item-state-inprocess">{$element.ASSESSED}</td><td class="item-state-solved">{$element.RESOLVED}</td><td class="closed">{$element.CLOSED}</td>
                            <td>{$element.CountUsers}</td> 
                            <td>{$element.CreateDateTime}</td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        {else}
            <span>�������� ���</span>
        {/if}
    </div>
</div>
{/block}