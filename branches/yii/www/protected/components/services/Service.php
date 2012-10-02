<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Services
 *
 * @author EntityFX
 */
abstract class Service {

    /**
     *
     * @var CDbConnection
     */
    protected $db;

    public function __construct()
    {
        $this->db = Yii::app()->db;
    }

}

?>
