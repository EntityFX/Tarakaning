{extends file="InfoBasePage.base.tpl"}
{block name=body}
<form class="form-horizontal" action="/search/result/" method="get">
    <fieldset>
        <legend>�������������� �������</legend>
    </fieldset>
    <div class="control-group">
        <label class="control-label" for="by_proj">���</label>
        <div class="controls">
            <input type="text" class="input-large" name="by_proj" id="Name" value="" />
        </div>
    </div>
    <div class="form-actions">
        <input class="btn btn-primary" type="submit" value="�����" />
    </div>
</form>
{/block}