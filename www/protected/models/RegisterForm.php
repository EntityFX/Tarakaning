<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RegisterForm
 *
 * @author EntityFX
 */
class RegisterForm extends CFormModel {

    public $email;
    public $password;
    public $commitPassword;
    public $code;
    public $name;
    public $surname;
    public $secondName;

    public function rules() {
        return array(
            // rememberMe needs to be a boolean
            array('email, password, commitPassword', 'required'),
            array('email', 'email'),
            //array('email', 'unique'),
            array('password', 'length', 'min' => 7),
            array('commitPassword', 'compare', 'compareAttribute' => 'password'),
            array('code', 'captcha'),
            array('name', 'safe'),
            array('surname', 'safe'),
            array('secondName', 'safe'),
        );
    }

}

?>
