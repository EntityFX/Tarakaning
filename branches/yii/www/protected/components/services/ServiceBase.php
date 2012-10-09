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
abstract class ServiceBase {

    /**
     *
     * @var CDbConnection
     */
    protected $db;

    public function __construct() {
        $this->db = Yii::app()->db;
    }

    /**
     * Returns ORDER BY token for field enum and order enum
     * 
     * @param AEnum $orderField Field to order
     * @param MySQLOrderENUM $direction Order direction
     * @return string 
     */
    protected function order(AEnum $orderField, MySQLOrderENUM $direction) {
        return $orderField->getValue() . ' ' . $direction->getValue();
    }

    /**
     * Returns count items in table
     * 
     * @param string $table SQL table name
     * @param mixed $where where condition
     * @param array $params parameters to bind in
     * @return int Count items 
     */
    protected function getCount($table, $where, array $params) {
        return $this->db->createCommand()
                        ->select('COUNT(*)')
                        ->from($table)
                        ->where($where, $params)
                        ->queryScalar();
    }

}

?>
