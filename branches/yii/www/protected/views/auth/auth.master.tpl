{*
Masterpage template
*}
{extends file="../layouts/main.master.tpl"}

{block name=body}
<div class="container">
    <header id="headmain">
        <div class="inner">
            <h1>Tarakaning</h1>
            <p>Система управления проектами, задачами и дефектами</p>
        </div>
    </header>
    <div class="row" id="userForm" >
        {block name=authForm}{/block}
    </div>
</div>
{/block}