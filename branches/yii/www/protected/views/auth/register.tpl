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
                        'id' => 'register-form',
                        'enableClientValidation' => true,
                        'clientOptions' => [
                            'validateOnSubmit' => true
                        ],
                        'htmlOptions' => [
                            'class' => 'well form-horizontal'
                        ],
                        'method' => 'post',
                        'action' => CHtml::normalizeUrl(
                            ['register']
                        )
                    ]
                )
    }
        <fieldset>
            <legend>Регистрация
                <a href="{$this->createUrl("site/index")}" class="btn form-header-right">На главную
                    <i class="icon-arrow-right"></i>
                </a>
            </legend>
            <div class="control-group">
                {$form->label($model,'email',['class' => 'control-label input-mini'])}
                <div class="controls">
                    {$form->textField($model,'email',['class' => 'input-large'])}
                    <span class="help-inline required">*</span>
                </div>
            </div>
            <div class="control-group">
                {$form->label($model,'password',['class' => 'control-label input-mini'])}
                <div class="controls">
                    {$form->passwordField($model,'password',['class' => 'input-large'])}
                    <span class="help-inline required">*</span>
                    <p class="help-block">Длина пароля не менее 7 символов</p>
                </div>
            </div>
            <div class="control-group">
                {$form->label($model,'commitPassword',['class' => 'control-label input-mini'])}
                <div class="controls">
                    {$form->passwordField($model,'commitPassword',['class' => 'input-large'])}
                    <span class="help-inline required">*</span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label input-mini">CAPTCHA</label>
                <div class="controls">
                    {captcha clickableImage=true}
                    <input type="hidden" name="captchaId" id="captchaId" value="{$CAPTCHA_ID}" />
                </div>
                <input type="hidden" name="captchaId" id="captchaId" value="{$CAPTCHA_ID}" />
            </div>
            <div class="control-group">
                {$form->label($model,'code',['class' => 'control-label input-mini'])}
                <div class="controls">
                    {$form->textField($model,'code',['class' => 'input-mini', 'maxlength' => '6'])}
                    <span class="help-inline required">*</span>
                </div>
            </div>
            <hr/>
            <div class="control-group">
                {$form->label($model,'name',['class' => 'control-label input-mini'])}
                <div class="controls">
                    {$form->textField($model,'name',['class' => 'input-xlarge'])}
                </div>
            </div>
            <div class="control-group">
                {$form->label($model,'surname',['class' => 'control-label input-mini'])}
                <div class="controls">
                    {$form->textField($model,'surname',['class' => 'input-xlarge'])}
                </div>
            </div>
            <div class="control-group">
                {$form->label($model,'secondName',['class' => 'control-label input-mini'])}
                <div class="controls">
                    {$form->textField($model,'secondName',['class' => 'input-xlarge'])}
                </div>
            </div>
            <div class="form-actions">
                {CHtml::submitButton('Зарегистрироваться',['class' => 'btn btn-large btn-primary'])}
            </div>
        </fieldset>
    {assign var=form value=$this->endWidget()}
{/block}