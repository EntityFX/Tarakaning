<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of profileController
 *
 * @author EntityFX
 */
class ProfileController extends ContentControllerBase {
    
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function actionEdit() {

        $modelList = array(
            'ProfileForm' => array(
                'performFunction' => 'updateUserDataOperation',
                'model' => new ProfileForm(Yii::app()->user),
            ),
            'ChangePasswordForm' => array(
                'performFunction' => 'updatePasswordOperation',
                'model' => new ChangePasswordForm(),
            ),
            'ProfileDefaultProjectForm' => array(
                'performFunction' => 'setDefaultProjectOperation',
                'model' => new ProfileDefaultProjectForm(),
            )
        );

        $this->updateModel($modelList);

        return $this->render('edit', array('model' => $this->prepareModel($modelList)));
    }
    
    public function actionIndex() {
        return $this->render('view', array('model' => Yii::app()->user));
    }
    
    private function prepareModel(&$modelList) {
        foreach ($modelList as $modelName => $modelItem) {
            $model[$modelName] = $modelItem['model'];
        }
        return $model;
    }

    protected function updatePasswordOperation(ChangePasswordForm $model) {
        $userService = $this->ioc->create('IUserService');
        try {
            $userService->changePassword(
                    Yii::app()->user->id, $model->oldPassword, $model->newPassword
            );
        } catch (ServiceException $serviceException) {
            $model->addError('passwordError', $serviceException->getMessage());
        }
    }

    protected function updateUserDataOperation(ProfileForm $model) {
        $userService = $this->ioc->create('IUserService');
        try {
            $userService->updateDataById(
                    Yii::app()->user->id, $model->firstname, $model->secondName, $model->lastName, $model->email
            );

            Yii::app()->user->firstname = $model->firstname;
            Yii::app()->user->secondName = $model->secondName;
            Yii::app()->user->lastName = $model->lastName;
            Yii::app()->user->email = $model->email;
        } catch (ServiceException $serviceException) {
            $model->addError('email', $serviceException->getMessage());
        }
    }

    protected function setDefaultProjectOperation(ProfileDefaultProjectForm $model) {
        $profileService = $this->ioc->create('IProfileService');
        try {
            $profileService->setDefaultProject(Yii::app()->user->id, $model->defaultProjectId);
            Yii::app()->user->defaultProjectId = $model->defaultProjectId; //update default project in User's sate
        } catch (ServiceException $serviceException) {
            $model->addError('defaultProjectError', $serviceException->getMessage());
        }
    }

}

?>