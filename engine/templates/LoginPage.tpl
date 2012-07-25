{extends file="MainBasePage.base.tpl"}
{block name=body}
<div class="container">
    <header id="headmain">
        <div class="inner">
            <h1>Tarakaning</h1>
            <p>Система управления проектами, задачами и дефектами</p>
        </div>
    </header>
    <div class="row" id="userForm" >
        {if $ERROR neq ""}
            <strong class="error" id="error">{$ERROR}</strong>
        {else if $GOOD eq TRUE}
            <strong class="ok" id="good">Пользователь зарегистрирован</strong>
        {/if}
        <form action="/login/do/" method="post" class="well form-horizontal">
            <fieldset>
                <legend>Войти в Tarakaning</legend>
            </fieldset>
            <div class="control-group">
                <label class="control-label" for="login">Логин</label>
                <div class="controls">
                    <input id="login" class="input-xlarge" type="text" name="login" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label input-mini" for="login">Пароль</label>
                <div class="controls">
                    <input id="pswrd" class="input-xlarge" type="password" name="pswrd" />
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <label class="checkbox">
                        <input type="checkbox"> Запомнить меня
                    </label>
                </div>
            </div>
            <div class="form-actions">
                <input type="submit" name="sigIn" id="sigIn" value="Войти" class="btn btn-large btn-primary" />
                <a href="/registration/" class="btn btn-large btn-success">Регистрация</a>
            </div>
        </form>
    </div>
</div>
{/block}