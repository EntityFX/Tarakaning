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
class ProjectController extends ContentControllerBase {

    public function actionIndex() {
        return $this->render('projects', array('projectsList' => null));
    }
}

?>
