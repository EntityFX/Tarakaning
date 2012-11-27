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

    public function actionIndex($userId) {

        $model = new ProfileForm(Yii::app()->user);

        $formData = $this->request->getPost("ProfileForm");
        if (isset($formData)) {
            $model->attributes = $formData;

            if ($model->validate()) {

                //change profile settings
                $this->redirect($application->user->returnUrl);
            }
        }

        return $this->render('edit', array('model' => $model));
    }

    public function actionEdit() {

        $model = new ProfileForm(Yii::app()->user);

        $formData = $this->request->getPost('ProfileForm');
        if (isset($formData)) {
            $model->attributes = $formData;

            if ($model->validate()) {

                $userService = $this->ioc->create('IUserService');
                try {
                    $userService->updateDataById(
                            Yii::app()->user->id, 
                            $model->firstname,
                            $model->secondName,
                            $model->lastName,
                            $model->email
                    );
                    
                    Yii::app()->user->firstname = $model->firstname;
                    Yii::app()->user->secondName = $model->secondName;
                    Yii::app()->user->lastName = $model->lastName;
                    Yii::app()->user->email = $model->email;
                }
                catch(ServiceException $serviceException){
                    $model->addError('email', $serviceException->getMessage());
                }
            }
        }

        return $this->render('edit', array('model' => $model));
    }

}

?>
