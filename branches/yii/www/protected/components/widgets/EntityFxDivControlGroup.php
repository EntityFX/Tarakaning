<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DivControlGroupWidget
 *
 * @author EntityFX
 */
class EntityFxDivControlGroup extends CWidget {

    public $htmlOptions = array();
    public $model = null;
    public $attribute = null;
    public $class = 'control-group';
    public $form = null;

    public function init() {
        $error = '';
        if ($this->model != null && $this->form != null) {
            $error = $this->model->hasErrors($this->attribute) ? ' '.$this->form->errorMessageCssClass : '';
        }

        echo "<div class=\"$this->class$error\">";
    }

    public function run() {
        echo "</div>";
    }

}

?>
