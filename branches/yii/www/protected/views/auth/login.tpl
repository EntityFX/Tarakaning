{*
Masterpage template
*}
{extends file="./auth.master.tpl"}

{block name=authForm}
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
                            ['login']
                        )
                    ]
                )
    }
        <fieldset>
            <legend>Войти в Tarakaning</legend>
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
                {CHtml::submitButton('Войти',['class' => 'btn btn-large btn-primary'])}
                {CHtml::link(
                                'Регистрация',
                                ['auth/register'],
                                ['class' => 'btn btn-large btn-success']
                            )}
            </div>
        </fieldset>
    {assign var=form value=$this->endWidget()}
{/block}