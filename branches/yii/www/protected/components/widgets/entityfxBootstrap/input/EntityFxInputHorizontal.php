<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

Yii::import('bootstrap.widgets.input.TbInputHorizontal');

/**
 * Description of EntityFxInput
 *
 * @author Administrator
 */
class EntityFxInputHorizontal extends TbInputHorizontal {

    /**
     * Container css class
     * @var string 
     */
    public $containerCssClass = '';

    protected function getContainerCssClass() {
        return $this->containerCssClass . ' ' . parent::getContainerCssClass();
    }

    /**
     * Processes the html options.
     */
    protected function processHtmlOptions() {
        parent::processHtmlOptions();
        if (isset($this->htmlOptions['containerCssClass'])) {
            $this->containerCssClass = $this->htmlOptions['containerCssClass'];
            unset($this->htmlOptions['containerCssClass']);
        }
    }

}

?>
