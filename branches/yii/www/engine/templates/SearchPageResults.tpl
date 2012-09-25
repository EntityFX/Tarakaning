{extends file="InfoBasePage.base.tpl"}

{block name=body}
    <div class="row">
        <div class="span5">
            <form class="well form-search" action="/search/" method="get">
                <input type="text" class="input-xlarge" name="by_proj" id="Name" value="{$SEARCH_QUERY}"  />
                <input class="btn" type="submit" value="Найти" name="search" />
            </form>  
        </div>
    </div>

    {$ERROR}
    {if $SEARCH_QUERY neq ''}
        {if $AR_SEARCH_ITEM neq NULL}
            <div class="groupier">
                {if $COUNT neq NULL}Количество результатов поиска: {$COUNT}{/if}
                {$PROJECT_SEARCH_PAGINATOR}
            </div>
            <div class="row">
                {foreach name=searchResult from=$AR_SEARCH_ITEM item=element}
                    <div class="well">
                        <h3>{$element.PROJ_NM}</h3>
                        <span class="author">автор:</span>
                        <span><a href="/profile/show/{$element.USER_ID}/">{$element.NickName}</a></span>
                        <p>{$element.DESCR}</p>
                        {if $element.ProjectRelation eq 0}
                            <form action="#" method="post" class="form-inline">
                                <div>
                                    <input type="hidden" value="{$element.PROJ_ID}" name="projectID" />
                                    <input type="submit" value="Подать заявку" class="btn btn-primary" />
                                </div>		
                            </form>
                        {elseif $element.ProjectRelation eq 1}
                            <form action="#" method="post" class="form-inline">
                                <div>
                                    <input type="submit" value="Прервать участие" class="btn btn-danger" />
                                </div>		
                            </form>
                        {else}
                            <span class="label label-success">Вы владелец</span>
                        {/if}
                    </div>
                {/foreach}
            </div>
        {else}
            <span>По запросу "<strong>{$SEARCH_QUERY}</strong>" ничего не найдено.</span>
        {/if}
{/if}
{/block}