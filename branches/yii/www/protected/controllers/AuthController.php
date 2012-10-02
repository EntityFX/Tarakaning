<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserController
 *
 * @author EntityFX
 */
class AuthController extends ControllerBase {

    /**
     * Displays the login page and performs login
     */
    public function actionLogin() {
        $userService = new UserService();
        var_dump($userService->getById(1),$userService->getByIdentity("GreenDragon"),$userService->existsByIdentity("GreenDragon"));
        $model = new LoginForm();

        $formData = $this->request->getPost("LoginForm");

        if (isset($formData)) {
            $model->attributes = $formData;

            if ($model->validate() && $model->login()) {
                $this->redirect($application->user->returnUrl);
            }
        }

        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionRegister() {
        $model = new RegisterForm();

        $formData = $this->request->getPost('RegisterForm');

        if (isset($formData)) {
            $model->attributes = $formData;

            if ($model->validate()) {

            }
        }

        $this->render('register', array('model' => $model));
    }

    public function actions() {
        return array(
            'captcha' => array(
                'class' => 'EntityFxCaptchaAction',
                'minLength' => 6,
                'maxLength' => 6,
                'height' => 80,
                'width' => 170,
                'transparent' => true,
                'fontFile' => YII::app()->getBasePath() .'/components/captcha/verdana.TTF'
            ),
        );
    }

}

?>
