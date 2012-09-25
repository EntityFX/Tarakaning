{extends file="InfoBasePage.base.tpl"}
{block name=body}
{if $ERROR neq ""}
    <div class="alert alert-error">
        <a class="close" data-dismiss="alert" href="#">&times;</a>
        {$ERROR}
    </div>
{/if}
{if $GOOD eq TRUE}
    <div class="alert alert-success">
        <a class="close" data-dismiss="alert" href="#">&times;</a>
        <span>��������� ���������</span>
    </div>
{/if}
<ul class="nav nav-tabs" id="item-tab">
    <li class="active"><a href="#edit_info" data-toggle="tab">������������� ����������</a></li>
    <li><a href="#pass_change" data-toggle="tab">����� ������</a></li>
    <li><a href="#settings" data-toggle="tab">�������������� ���������</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="edit_info">
        <form class="form-horizontal" action="/profile/edit/" method="post">
            <fieldset>
                <legend>�������������� �������</legend>
            </fieldset>
            <div class="control-group">
                <label class="control-label" for="Name">���</label>
                <div class="controls">
                    <input type="text" class="input-large" name="Name" id="Name" value="{$AR_USER_INFO.FRST_NM}" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="Surname">�������</label>
                <div class="controls">
                    <input type="text" class="input-large" name="Surname" id="Surname" value="{$AR_USER_INFO.LAST_NM}" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="SecondName">��������</label>
                <div class="controls">
                    <input type="text" class="input-large" name="SecondName" id="SecondName" value="{$AR_USER_INFO.SECND_NM}" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="Email">e-mail</label>
                <div class="controls">
                    <input type="text" class="input-large" name="Email" id="Email" value="{$AR_USER_INFO.EMAIL}" />
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit" name="save_profile">
                    <i class="icon-hdd icon-white"></i>
                    ���������
                </button>
            </div>
        </form>
    </div>
    <div class="tab-pane" id="pass_change">
        <form class="form-horizontal" action="/profile/edit/newpass/" method="post">
            <fieldset>
                <legend>����� ������</legend>
            </fieldset>
            <div class="control-group">
                <label class="control-label" for="oldPassword">������ ������</label>
                <div class="controls">
                    <input type="password" class="input-large" name="oldPassword" id="oldPassword" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="newPassword">����� ������</label>
                <div class="controls">
                    <input type="password" class="input-large" name="newPassword" id="newPassword" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="newPasswordRepeat">������ ������ ������</label>
                <div class="controls">
                    <input type="password" class="input-large" name="newPasswordRepeat" id="newPasswordRepeat" />
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit" name="save_btn">
                    <i class="icon-hdd icon-white"></i>
                    ���������
                </button>
            </div>
        </form>
    </div>
    <div class="tab-pane" id="settings">
        <form class="form-horizontal" action="/profile/edit/" method="post">
            <fieldset>
                <legend>����� ���������</legend>
            </fieldset>
            <div class="control-group">
                <label class="control-label" for="defaultProject">������ ��-���������</label>
                <div class="controls">
                    <select id="defaultProject" name="defaultProject" {if $PROJECTS.PROJECTS_LIST eq NULL}disabled="disabled"{/if}>
                        {if $PROJECTS.PROJECTS_LIST neq NULL}
                            {html_options options=$PROJECTS.PROJECTS_LIST selected=$PROJECTS.selected}
                        {/if}
                    </select>
                </div>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit" name="save_project" {if $PROJECTS.PROJECTS_LIST eq NULL}disabled="disabled"{/if}>
                    <i class="icon-hdd icon-white"></i>
                    ���������
                </button>
            </div>
        </form>
    </div>                
</div>
{/block}