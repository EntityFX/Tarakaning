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
        <span>Изменения сохранены</span>
    </div>
{/if}
<form class="form-horizontal" action="" method="post">
    <fieldset>
        <legend>Редактирование проекта</legend>
        <div class="control-group">
            <label class="control-label" for="project_name">Имя проекта</label>
            <div class="controls">
                <input type="text" class="input-xlarge" name="project_name" id="project_name" value="{$PROJECT_DATA.Name}" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="description">Описание проекта</label>
            <div class="controls">
                <textarea name="description" id="description" class="input-xxlarge" rows="10">{$PROJECT_DATA.Description}</textarea>
            </div>
        </div>
        <div class="form-actions">
            <input class="btn btn-primary" type="submit" value="Сохранить изменения" name="save" />
        </div>
        <input type="hidden" name="project_id" value="{$PROJECT_DATA.ProjectID}" />	
    </fieldset>
</form>
{/block}