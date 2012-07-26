{extends file="MainBasePage.base.tpl"}
{block name=script}
<script type="text/javascript">
    /* <![CDATA[ */
    $(document).ready(function() {
    $("#tabs").tabs();
    $("input:button, input:submit, a").button();
});
/* ]]>*/
</script>
{/block}

{block name=body}
<div class="container">
    <header id="headmain">
        <div class="inner">
            <h1>Tarakaning</h1>
            <p>Система управления проектами, задачами и дефектами</p>
        </div>
    </header>
    {if $ERROR neq ""}
        <div class="alert alert-error" id="error">
            <a class="close" data-dismiss="alert" href="#">&times;</a>
            {$ERROR}
        </div>
    {/if}
    <div class="row" id="userForm" >
        <form action="/registration/do/" method="post" id="userForm" class="well form-horizontal">
            <fieldset>
                <legend>Регистрация</legend>
            </fieldset>
            <div class="control-group">
                <label class="control-label" for="login">Логин</label>
                <div class="controls">
                    <input id="login" class="input-xlarge" type="text" name="login" value="{$DATA.login}"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label input-mini" for="password">Пароль</label>
                <div class="controls">
                    <input id="password" class="input-xlarge" type="password" name="password" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label input-mini" for="commitPass">Подтверди пароль</label>
                <div class="controls">
                    <input id="commitPass" class="input-xlarge" type="password" name="commitPass" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label input-mini" for="">CAPTCHA</label>
                <div class="controls">
                    {$CAPTCHA}
                    <input type="hidden" name="captchaId" id="captchaId" value="{$CAPTCHA_ID}" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label input-mini" for="captcha">Введите код</label>
                <div class="controls">
                    <input id="captcha" class="input-mini" type="text" name="captcha" maxlength="6" />
                </div>
            </div> 
            <hr/>
            <div class="control-group">
                <label class="control-label input-mini" for="name">Имя</label>
                <div class="controls">
                    <input id="name" class="input-xlarge" type="text" name="name" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label input-mini" for="name">Фамилия</label>
                <div class="controls">
                    <input id="name" class="input-xlarge" type="text" name="name" value="{$DATA.name}" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label input-mini" for="secondName">Отчество</label>
                <div class="controls">
                    <input id="secondName" class="input-xlarge" type="text" name="secondName" value="{$DATA.surname}" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label input-mini" for="eMail">E-mail</label>
                <div class="controls">
                    <input id="eMail" class="input-xlarge" type="text" name="eMail" value="{$DATA.eMail}" />
                </div>
            </div>
            <div class="form-actions">
                <input type="submit" id="sigIn" value="Зарегистрироваться" class="btn btn-large btn-primary" />
            </div>
        </form>
    </div>
</div>
{/block}