<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProjectController
 *
 * @author EntityFX
 */
class ProjectController extends EntityFxControllerBase {

    public function actionIndex() {
        //CVarDumper::dump(Yii::app()->user->getEmail(), 10, true);
        return $this->render('projects', array('projectsList' => null));
    }

}

?>
