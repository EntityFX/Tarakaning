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
            <div class="alert alert-error">
                <a class="close" data-dismiss="alert" href="#">&times;</a>
                {$ERROR}
            </div>
        {/if}
        {if $GOOD eq TRUE}
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert" href="#">&times;</a>
                <span>Пользователь зарегистрирован</span>
            </div>
        {/if}
        <form action="/login/do/" method="post" class="well form-horizontal">
            <fieldset>
                <legend>Войти в Tarakaning</legend>
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
            </fieldset>
        </form>
    </div>
</div>
{/block}