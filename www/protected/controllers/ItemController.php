<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ItemController
 *
 * @author EntityFX
 */
class ItemController extends ContentControllerBase {
    
    public function filters()
    {
        return array(
            'accessControl',
        );
    }
    
    public function actionAdd() {
        
        $model = new AddItemForm();
        
        return $this->render('add', array('model' => $model));
    }
}

?>
