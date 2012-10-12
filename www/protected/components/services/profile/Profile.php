<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Profile
 *
 * @author Администратор
 */
class Profile {

    public $login;
    public $name;
    public $surname;
    public $secondName;
    public $mail;
    public $id;
    public $defaultProjectID;
    private $_passwordHash;
    
    /**
     * Устанавливает состояния полей из массива
     * 
     * @param array $resArray
     */
    private function __construct($resArray) {
        $this->login = $resArray["NICK"];
        $this->name = $resArray["FRST_NM"];
        $this->secondName = $resArray["SECND_NM"];
        $this->surname = $resArray["LAST_NM"];
        $this->mail = $resArray["EMAIL"];
        $this->id = (int) $resArray["USER_ID"];
        $this->_passwordHash = $resArray["PASSW_HASH"];
        $this->defaultProjectID = $resArray["DFLT_PROJ_ID"];
    }

}

?>
