{extends file="InfoBasePage.base.tpl"}
{block name=body}
<div class="span2">
</div>
<div class="span10">
    <div class="row">
        <div class="span1">Логин</div>
        <div class="span2">{if $AR_USER_INFO.NICK eq ""}&nbsp;{else}{$AR_USER_INFO.NICK}{/if}</div>
    </div>
    <div class="row">
        <div class="span1">Имя</div>
        <div class="span2">{if $AR_USER_INFO.FRST_NM eq ""}&nbsp;{else}{$AR_USER_INFO.FRST_NM}{/if}</div>
    </div>
    <div class="row">
        <div class="span1">Фамилия</div>
        <div class="span2">{if $AR_USER_INFO.LAST_NM eq ""}&nbsp;{else}{$AR_USER_INFO.LAST_NM}{/if}</div>
    </div>
    <div class="row">
        <div class="span1">Отчество</div>
        <div class="span2">{if $AR_USER_INFO.SECND_NM eq ""}&nbsp;{else}{$AR_USER_INFO.SECND_NM}{/if}</div>
    </div>
    <div class="row">
        <div class="span1">e-mail</div>
        <div class="span2">{if $AR_USER_INFO.EMAIL eq ""}&nbsp;{else}<a href="mailto:{$AR_USER_INFO.EMAIL}">{$AR_USER_INFO.EMAIL}</a>{/if}</div>
    </div>
</div>
{/block}