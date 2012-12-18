<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AddProjectForm
 *
 * @author Artem
 */
class AddProjectForm extends CFormModel {
    public $projectName;
    
    public $projectDescription;
    
    public function rules() {
        return array(
            array('projectName', 'required'),
            array('projectDescription', 'safe')
        );
    }
    
    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'projectName'    => Yii::t('global', '* Название проекта'),
            'projectDescription'      => Yii::t('global', '* Описание')
        );
    }
}

?>
