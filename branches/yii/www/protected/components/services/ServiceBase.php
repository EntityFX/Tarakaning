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
    
    /**
     * ioc container
     * 
     * @var Phemto 
     */
    protected $ioc;

    public function __construct() {
        $this->db = Yii::app()->db;
        $this->ioc = Ioc::create();
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
        return (int) $this->db->createCommand()
                        ->select('COUNT(*)')
                        ->from($table)
                        ->where($where, $params)
                        ->queryScalar();
    }
    
    /**
     * Returns id of inserted record
     * 
     * @return int 
     */
    protected function lastInsertId()
    {
        return (int)$this->db->createCommand('SELECT LAST_INSERT_ID()')
                ->queryScalar();
    }
    
    /**
     * Checks if user subscibed to project
     * 
     * @param int $userId
     * @param int $projectId
     * @return bool 
     */
    protected function isMember($userId, $projectId) {
        
        $subscribeService = $this->ioc->create('ISubscribeService');
        $projectService = $this->ioc->create('IProjectService');
        return $subscribeService->isSubscribed($userId, $projectId)
                || $userId == $projectService->getOwnerID($projectId);
    }
    
    /**
     * Checks if user subscibed to project or throw expetion
     * 
     * @param int $userId
     * @param project $projectId
     * @throws ServiceException 
     */
    protected function tryCheckIsSubscribedOrOwner($userId, $projectId) {
        if (!$this->isMember($userId, $projectId)) {
            throw new ServiceException("Пользователь №" . $userId . " не подписан на проект $projectId или не является его владельцем");
        }
    }

}

?>
