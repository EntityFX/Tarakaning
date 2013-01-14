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
    public $id;
    
    public $name;
    
    public $description;
    
    public $author;
    
    public $authorId;
    
    public function rules() {
        return array(
            array('name', 'required'),
            array('description', 'safe')
        );
    }
    
    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'projectName'           => Yii::t('global', '* Название проекта'),
            'projectDescription'    => Yii::t('global', '* Описание')
        );
    }
    
    /**
     * Creates project's model by service data
     * @param array $data
     * @return AddProjectForm 
     */
    public static function createByProjectServiceData(&$data) {
        $model = new AddProjectForm();
        $model->id = (int)$data[ProjectAndOwnerNickView::PROJECT_ID_FIELD];
        $model->name = $data[ProjectAndOwnerNickView::PROJECT_NAME_FIELD]; 
        $model->description = $data[ProjectAndOwnerNickView::DESCRIPTION_FIELD]; 
        $model->author = $data[ProjectAndOwnerNickView::OWNER_NICK_NAME_FIELD];
        $model->authorId = (int)$data[ProjectAndOwnerNickView::OWNER_ID_FIELD];
        return $model;
    }
}

?>
