<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProfileDefaultProjectForm
 *
 * @author Artem
 */
class ProfileDefaultProjectForm extends CFormModel  {
    public $defaultProjectId;
    
    public function rules() {
        return array(
            array('defaultProjectId', 'safe'),
        );
    }
}

?>
