<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ChangePasswordForm
 *
 * @author Artem
 */
class ChangePasswordForm extends CFormModel {
    public $oldPassword;
    public $newPassword;
    public $confirmPassword;
    
    public function rules() {
        return array(
            // rememberMe needs to be a boolean
            array('oldPassword, newPassword, confirmPassword', 'required'),
            //array('email', 'unique'),
            array('oldPassword, newPassword', 'length', 'min' => 7),
            array('confirmPassword', 'compare', 'compareAttribute' => 'newPassword'),
        );
    }
}

?>
