{extends file="InfoBasePage.base.tpl"}
{block name=body}
{if $ERROR neq ""}
    <div class="alert alert-error" id="error">
        <a class="close" data-dismiss="alert" href="#">&times;</a>
        {$ERROR}
    </div>
{/if}
<form class="form-horizontal" action="" method="post">
    <fieldset>
        <legend>Создание проекта</legend>
        <div class="control-group">
            <label class="control-label" for="project_name">Имя проекта</label>
            <div class="controls">
                <input type="text" class="input-xlarge" name="project_name" id="project_name" value="{$DATA.project_name}" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="description">Описание проекта</label>
            <div class="controls">
                <textarea name="description" id="description" class="input-xxlarge" rows="10">{$DATA.description}</textarea>
            </div>
        </div>
        <div class="form-actions">
            <input class="btn btn-primary" type="submit" value="Создать проект" />
        </div>
    </fieldset>
</form>
{/block}