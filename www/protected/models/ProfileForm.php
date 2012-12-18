<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProfileForm
 *
 * @author Artem
 */
class ProfileForm extends CFormModel {
    
    public $email;
    public $firstname;
    public $secondName;
    public $lastName;
    
    public function __construct(CWebUser $userData, $scenario = '') {
        parent::__construct($scenario);
        $this->email = $userData->email;
        $this->firstname = $userData->firstname;
        $this->secondName = $userData->secondName;
        $this->lastName = $userData->lastName;
    }

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            array('email', 'email'),
            array('firstname, secondName, lastName', 'length', 'max' => 35),
            array('firstname', 'safe'),
            array('secondName', 'safe'),
            array('lastName', 'safe'),
        );
    }
}

?>
