{extends file="MainBasePage.base.tpl"}
{block name=body}
<div class="container">
    <header id="headmain">
        <div class="inner">
            <h1>Tarakaning</h1>
            <p>������� ���������� ���������, �������� � ���������</p>
        </div>
    </header>
    <div class="row" id="userForm" >
        {if $ERROR neq ""}
            <strong class="error" id="error">{$ERROR}</strong>
        {else if $GOOD eq TRUE}
            <strong class="ok" id="good">������������ ���������������</strong>
        {/if}
        <form action="/login/do/" method="post" class="well form-horizontal">
            <fieldset>
                <legend>����� � Tarakaning</legend>
            </fieldset>
            <div class="control-group">
                <label class="control-label" for="login">�����</label>
                <div class="controls">
                    <input id="login" class="input-xlarge" type="text" name="login" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label input-mini" for="login">������</label>
                <div class="controls">
                    <input id="pswrd" class="input-xlarge" type="password" name="pswrd" />
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <label class="checkbox">
                        <input type="checkbox"> ��������� ����
                    </label>
                </div>
            </div>
            <div class="form-actions">
                <input type="submit" name="sigIn" id="sigIn" value="�����" class="btn btn-large btn-primary" />
                <a href="/registration/" class="btn btn-large btn-success">�����������</a>
            </div>
        </form>
    </div>
</div>
{/block}