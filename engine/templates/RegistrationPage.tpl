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
            <h4 class="alert-heading">Ошибка!</h4>
            {$ERROR}
        </div>
    {/if}
    <div class="row" id="userForm" >
        <form action="/registration/do/" method="post" class="well form-horizontal">
            <fieldset>
                <legend>Регистрация
                    <a href="/" class="btn form-header-right">На главную
                        <i class="icon-arrow-right"></i>
                    </a>
                </legend>
                <div class="control-group">
                    <label class="control-label" for="login">Логин </label>
                    <div class="controls">
                        <input id="login" class="input-large" type="text" name="login" value="{$DATA.login}"/>
                        <span class="help-inline required">*</span>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label input-mini" for="password">Пароль</label>
                    <div class="controls">
                        <input id="password" class="input-large" type="password" name="password" />
                        <span class="help-inline required">*</span>
                        <p class="help-block">Длина пароля не менее 7 символов</p>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label input-mini" for="commitPass">Повтори пароль</label>
                    <div class="controls">
                        <input id="commitPass" class="input-large" type="password" name="commitPass" />
                        <span class="help-inline required">*</span>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label input-mini">CAPTCHA</label>
                    <div class="controls">
                        {$CAPTCHA}
                        <button type="button" name="refresh" id="refresh" value="1" class="btn btn-large" title="Обновить код">
                            <i class="icon-retweet"></i>
                        </button>
                        <input type="hidden" name="captchaId" id="captchaId" value="{$CAPTCHA_ID}" />
                    </div>
                    <input type="hidden" name="captchaId" id="captchaId" value="{$CAPTCHA_ID}" />
                </div>
                <div class="control-group">
                    <label class="control-label input-mini" for="captcha">Введите код</label>
                    <div class="controls">
                        <input id="captcha" class="input-mini" type="text" name="captcha" maxlength="6" />
                        <span class="help-inline required">*</span>
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
                    <label class="control-label input-mini" for="surname">Фамилия</label>
                    <div class="controls">
                        <input id="surname" class="input-xlarge" type="text" name="surname" value="{$DATA.surname}" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label input-mini" for="secondName">Отчество</label>
                    <div class="controls">
                        <input id="secondName" class="input-xlarge" type="text" name="secondName" value="{$DATA.secondName}" />
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
            </fieldset>
        </form>
    </div>
</div>
{/block}