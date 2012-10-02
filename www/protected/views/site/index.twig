{*
Masterpage template
*}
{extends file="../layouts/main.master.tpl"}

{block name=body}
<div class="container">
    <header id="headmain">
        <div class="inner">
            <h1>EntityFX</h1>
            <p>* Projects and bugs tracking system</p>
        </div>
    </header>
    <div class="row" id="userForm" >
        {if $GOOD eq TRUE}
            <div class="alert alert-success">
                <a class="close" data-dismiss="alert" href="#">&times;</a>
                <span>Пользователь зарегистрирован</span>
            </div>
        {/if}
        
        {CHtml::errorSummary($model,
            '<div class="alert alert-error">
                <a class="close" data-dismiss="alert" href="#">&times;</a>',
            '</div>'
        )}
        {assign var=form value=$this->beginWidget(
                'CActiveForm', 
                [
                    'id' => 'login-form',
                    'enableClientValidation' => true,
                    'clientOptions' => [
                        'validateOnSubmit' => true
                    ],
                    'htmlOptions' => [
                        'class' => 'well form-horizontal'
                    ],
                    'method' => 'post',
                    'action' => CHtml::normalizeUrl(
                        ['auth/login']
                    )
                ]
            )
        }
            <fieldset>
                <legend>* Enter to EntityFX</legend>
                <div class="control-group">
                    {$form->label($model,'username',['class' => 'control-label'])}
                    <div class="controls">
                        {$form->textField($model,'username',['class' => 'input-xlarge'])}
                    </div>
                </div>
                <div class="control-group">
                    {$form->label($model,'password',['class' => 'control-label'])}
                    <div class="controls">
                        {$form->passwordField($model,'password',['class' => 'input-xlarge'])}
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <label class="checkbox">
                            {$form->checkBox($model,'rememberMe')} {$model->getAttributeLabel('rememberMe')}
                        </label>
                    </div>
                </div>
                <div class="form-actions">
                    {CHtml::submitButton('*Sign In',['class' => 'btn btn-large btn-primary'])}
                    {CHtml::link(
                        '*Sign up',
                        ['auth/register'],
                        ['class' => 'btn btn-large btn-success']
                    )}
                </div>
            </fieldset>
        {assign var=form value=$this->endWidget()}
    </div>
</div>
{/block}