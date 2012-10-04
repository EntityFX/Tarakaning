<?php

class SiteController extends EntityFxControllerBase {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {

        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionIndex() {
        $model = new LoginForm;
        $this->render('index', array('model' => $model));
    }

    public function actionTest() {
        $userService = new UserService();
        var_dump(
            /*$userService->getById(1), 
            $userService->getByIdentity("GreenDragon"), 
            $userService->existsByIdentity("GreenDragon"),
            $userService->deleteById(16),
            $userService->getAll(),
            $userService->changeUserType(15, true),
            $userService->diactivateById(15),*/
            //$userService->getAllByFirstLetter('v')
            //$userService->updateDataById(15, 'Hrenov', 'Hren', "Hrenovich", 'tym_s@mail.ru')
            //$userService->setRandomPasswordById(15,9)
            //$userService->changePassword(15,'5BLM7iorC','asdfgaa')
            $userService->create('fg@w.ru', '1234567',1,'132','sdfd','fthfgh','hgfh@sfgf.ty')
        );
    }

}