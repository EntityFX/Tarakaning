<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

Yii::import('bootstrap.widgets.TbActiveForm');
Yii::import('application.components.widgets.entityfxBootstrap.input.EntityFxInputHorizontal');

/**
 * Description of EntityFxActiveForm
 *
 * @author Administrator
 */
class EntityFxActiveForm extends TbActiveForm {

    const ENTITY_INPUT_HORIZONTAL = 'EntityFxInputHorizontal';
    
    /**
     * Returns the input widget class name suitable for the form.
     * @return string the class name
     */
    protected function getInputClassName() {
        if (isset($this->input))
            return $this->input;
        else {
            switch ($this->type) {
                case self::TYPE_HORIZONTAL:
                    return self::ENTITY_INPUT_HORIZONTAL;
                    break;

                case self::TYPE_INLINE:
                    return self::INPUT_INLINE;
                    break;

                case self::TYPE_SEARCH:
                    return self::INPUT_SEARCH;
                    break;

                case self::TYPE_VERTICAL:
                default:
                    return self::INPUT_VERTICAL;
                    break;
            }
        }
    }

}

?>
