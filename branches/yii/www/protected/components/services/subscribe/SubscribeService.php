<?php

/**
 * Класс управления подписками на проект.
 * @author EntityFX
 *
 */
class SubscribeService extends ServiceBase implements ISubscribeService {
    /*
     *  1) получить список проектов, в которых участвует пользователь (для меня минимум) (из таблицы UsersInProjects)
      2) прервать участие в данном проекте (удаление записи из таблицы UsersInProjects)
      3) подать заявку на проект (запись в таблицу SubscribesRequest)
     */

    const TABLE_SUBSCR_RQST = 'SUBSCR_RQST';
    const TABLE_USER_IN_PROJ = 'USER_IN_PROJ';
    const VIEW_SUBSCRIBES_DETAIL = 'view_SubscribesDetails';
    const VIEW_SUBSCRIBES_USER_NICK = 'view_SubscribesUserNick';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Проверяется есть ли нерассмотренная заявка от данного пользователя на данный проект.
     * @param int $userID - id пользователя, подавшего заявку.
     * @param int $projectID - id проекта, на который пользователь подал заявку.
     * @return bool - результат проверки.
     */
    public function isRequestExists($userID, $projectID) {
        $userID = (int) $userID;
        $projectID = (int) $projectID;
        $p = new ProjectService();
        if ($p->existsById($projectID)) {
            return $this->db->createCommand()
                            ->select('SUBSCR_RQST_ID')
                            ->from(self::TABLE_SUBSCR_RQST)
                            ->where(
                                    array(
                                        'and',
                                        'PROJ_ID = :projectId',
                                        'USER_ID = :userId'
                                    )
                                    , array(
                                        ':projectId' => $projectID,
                                        ':userId' => $userID
                                    )
                            )
                            ->queryScalar() !== false;
        } else {
            throw new ServiceException("Проект не существует.", 101);
        }
    }

    /**
     * Получение списка проектов, на которые подписан пользователь.
     * @param int $userID - id пользователя, подавшего заявку.
     */
    public function getUserSubscribes($userID, SubscribesDetailENUM $orderField, MySQLOrderEnum $direction, $page = 0, $size = 15) {
        $userID = (int) $userID;
        return $this->db->createCommand()
                ->select()
                ->from(self::VIEW_SUBSCRIBES_DETAIL)
                ->where('UserID = :userId',array(':userId' => (int)$userID))
                ->order($this->order($orderField,$direction))
                ->limit($size, $page)
                ->queryAll();
    }

    public function getSubscribesCount($userID) {
        return $this->getCount(self::TABLE_SUBSCR_RQST, 'USER_ID = :userId',array(':userId' => (int)$userID));
    }

    public function getProjectSubscribes($projectID, ProjectSubscribesDetailENUM $orderField, MySQLOrderEnum $direction, $page = 0, $size = 15) {
        $projectID = (int) $projectID;
        return $this->db->createCommand()
                ->select()
                ->from(self::VIEW_SUBSCRIBES_USER_NICK)
                ->where('ProjectID = :projectId',array(':projectId' => (int)$projectID))
                ->order($this->order($orderField,$direction))
                ->limit($size, $page)
                ->queryAll();
    }

    /**
     * 
     * Возвращает количество заявок на проект
     * @param unknown_type $projectID
     */
    public function getProjectSubscribesCount($projectID) {
        return $this->getCount(self::TABLE_SUBSCR_RQST, 'PROJ_ID = :projectId',array(':projectId' => (int) $projectID));
    }

    /**
     * Удаление данного пользователя из списка участников проекта.
     * @param int $userID - id пользователя.
     * @param int $projectID - id проекта, из которого пользователь удаляется.
     */
    public function removeMember($userID, $projectID) {
        $userID = (int) $userID;
        $projectID = (int) $projectID; //если проект не существует, то вернет ошибку.
        if ($this->isRequestExists($userID, $projectID)) {
            $this->db->createCommand()
                    ->delete(
                            self::TABLE_USER_IN_PROJ, 
                            array(
                                'and',
                                'PROJ_ID = :projectId',
                                'USER_ID = :userID'
                            )
                            , 
                            array(
                                ':projectId' => $projectID,
                                ':userID' => $userID
                            )
                    );
        } else {
            throw new ServiceException("Вы не являетесь участником проекта.", 501);
        }
    }

    /**
     * 
     * Удаляет участников проекта
     * @param array $keysList Список идентификаторов участников
     * @param int $projectID ID проекта
     */
    public function deleteProjectMembers($keysList, $ownerID, $projectID) {
        $ownerID = (int) $ownerID;
        $projectID = (int) $projectID;
        $projectOperation = new ProjectService();
        if ($projectOperation->isOwner($ownerID, $projectID) && $keysList != '') {
            $keysListSerialized = SerializeHelper::SerializeForStoredProcedure($keysList);
            $query = 'CALL DeleteUsersFromProject(:projectId, :keysList)';
            $deleteCommand = $this->db->createCommand($query);
            $deleteCommand->bindParam(':projectId', $projectID);
            $deleteCommand->bindParam(':keysList', $keysListSerialized);
            $deleteCommand->execute();
        }
        else {
            throw new ServiceException("вы не являетесь владельцем проекта");
        }
    }

    public function getProjectUsers($projectID) {
        $p = new ProjectService();
        $projectID = (int) $projectID;
        if ($p->existsById($projectID)) {
            $ownerID = $p->getOwnerID($projectID);
            $tmp = $this->db->createCommand()
                    ->select('USER_ID')
                    ->from(self::TABLE_USER_IN_PROJ)
                    ->where(
                            'PROJ_ID = :projectId',
                            array(
                                ':projectId' => $projectID
                            )
                    )
                    ->queryAll();
            array_unshift($tmp, $ownerID);
            return $tmp;
        } else {
            throw new ServiceException("Проект не существует.", 101);
        }
    }

    public function getProjectUsersPaged($projectID, $page = 0, $size = 30) {
        $projectID = (int) $projectID;
        $p = new ProjectService();
        if ($p->existsById($projectID)) {
            $ownerID = $p->getOwnerID($projectID);
            $startIndex = (int) $startIndex;
            $maxCount = (int) $maxCount;
            $tmp = $this->db->createCommand()
                    ->select()
                    ->from(self::TABLE_USER_IN_PROJ)
                    ->where('PROJ_ID = :projectId',array(':projectId' => (int)$projectID))
                    ->limit($size, $page)
                    ->queryAll();
            $startIndex == 0 ? array_unshift($tmp, $ownerID) : TRUE;
            return $tmp;
        } else {
            throw new ServiceException("Проект не существует.", 101);
        }
    }
    
    /**
     * Проверяется подписан ли данный пользователь в данном проекте.
     * @param int $userID - id пользователя.
     * @param int $projectID - id проекта.
     */
    public function isSubscribed($userID, $projectID) {
        $userID = (int) $userID;
        $projectID = (int) $projectID;
        return $this->db->createCommand()
                ->select('PROJ_ID')
                ->from(self::TABLE_USER_IN_PROJ)
                ->where(
                    array(
                        'and',
                        'PROJ_ID = :projectId',
                        'USER_ID = :userId'
                    )
                    ,array(
                        ':projectId' => $projectID,
                        ':userId' => $userID
                    )
                )
                ->queryScalar() !== false;
    }

}

?>