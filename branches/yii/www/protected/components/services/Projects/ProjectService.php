<?php

/**
 * Класс управления проектами.
 * @author timur 27.01.2011
 *
 */
class ProjectService extends Service {

    const VIEW_ALL_USER_PROJECTS = 'view_AllUserProjects';
    const VIEW_PROJECT_AND_ERRORS = 'view_ProjectAndErrors';
    const VIEW_PROJECT_INFO_WITHOUT_OWNER = 'view_ProjectInfoWithoutOwner';
    const VIEW_PROJECT_AND_OWNER_NICK = 'view_ProjectAndOwnerNick';
    const VIEW_USER_IN_PROJ_ERROR_AND_COMM = 'view_UserInProjectErrorsAndComments';
    const TABLE_PROJ = 'PROJ';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Добавление нового проекта. Создано 27.01.2011.
     * @param $userId - id пользователя, создавшего проект.
     * @param $projectName - название проекта.
     * @param $description - описание проекта.
     * @return int Новый идентификатор проекта. 
     * 
     * @todo 1) проверку существования пользователя.<br />
     * 2) при добавлении проекта должно происходить добавление в таблицу истории.
     */
    public function addProject($userID, $projectName, $description) {
        if (trim($projectName) == '') {
            throw new ServiceException("Заголовок проекта не должен быть пустым");
        }
        $userID = (int) $userID;
        $this->db->createCommand()->insert(
                self::TABLE_PROJ, array(
                    'PROJ_NM' => htmlspecialchars($projectName),
                    'DESCR' => htmlspecialchars($description),
                    'USER_ID' => (int) $userID,
                    'CRT_TM' => date("c")
                )
        );
        return $this->db->getLastInsertID();
    }

    /**
     * Проверяет существует ли проект с ананлогичным названием.
     * @param string $name
     */
    public function existsByName($name) {
        $this->db->createCommand()
                        ->select('PROJ_NM')
                        ->from(self::TABLE_PROJ)
                        ->where(
                                'PROJ_NM=:projectName', 
                                array(
                                    ':projectName' => $name
                                )
                        )
                        ->queryScalar() !== false ? true : false;
    }

    /**
     * Обновление имени проекта. Обновить имя может только создатель проекта. Создано 28.01.2011.
     * @param int $userID - id пользователя, создавшего проект.
     * @param string $projectNewName - новое название проекта.
     * @param int $projectID - id проекта, подлежащего изменению названия.
     * 
     * @todo при изменении названия проекта происходить должно: <br /> 
     * добавление в таблицу истории проекта (-) <br />
     * и обновление в таблице проектов (+) <br />
     */
    public function updateProjectDataById($projectID, $userID, $projectNewName, $newDescription) {
        $userID = (int) $userID;
        $projectID = (int) $projectID;
        if ($this->isProjectExists($projectID)) {
            if ($projectNewName == '') {
                throw new ServiceException("Имя проекта не должно быть пустым");
            }
            if ($this->isOwner($userID, $projectID)) {
                $this->db->createCommand()
                        ->update(
                                self::TABLE_PROJ, 
                                array(
                                    'PROJ_NM' => $projectNewName,
                                    'DESCR'   => $newDescription,
                                    'PROJ_ID' => $projectID,
                                    'USER_ID' => $userID
                                ),
                                array(
                                    'and',
                                    'PROJ_ID' => ':projectId',
                                    'USER_ID' => ':userId'
                                ),
                                array(
                                    ':projectId'    => $projectID,
                                    ':userId'       => $userID
                                )
                        );
            } else {
                throw new ServiceException("Не Вы являетесь Создателем проекта.", 102);
            }
        } else {
            throw new ServiceException("Проект не существует.", 101);
        }
    }

    /**
     * Проверяется является ли пользователь автором проекта.
     * @param int $userID - id пользователя, создавшего проект.
     * @param int $projectID - id проекта, подлежащего изменению названия.
     */
    public function isOwner($userID, $projectID) {
        $userID = (int) $userID;
        $projectID = (int) $projectID;
        $ownerId = $this->db->createCommand()
                        ->select('USER_ID')
                        ->from(self::TABLE_PROJ)
                        ->where(
                                'PROJ_ID=:projectId', 
                                array(
                                    ':projectId' => $projectID
                                )
                        )
                        ->queryScalar();
        return ($userID == (int) $ownerId);
    }

    /**
     * Удаление проекта. Удалять проект может только создатель. Создано 28.01.2011.
     * @param int $userID
     * @param int $projectID
     * 
     * @todo добавить проверку пользователя - является ли администратором. Тогда можно будет и ему удалять.
     */
    public function deleteById($userID, $projectID) {
        $userID = (int) $userID;
        $projectID = (int) $projectID;
        if ($this->isProjectExists($projectID)) {
            if ($this->isOwner($userID, $projectID)) {
                $this->db->createCommand()
                        ->delete(
                                self::TABLE_PROJ, 
                                'PROJ_ID=:projectId', 
                                array(
                                    ':projectId' => $projectID
                                )
                        );
            } else {
                throw new ServiceException("Не Вы являетесь Создателем проекта.", 102);
            }
        } else {
            throw new ServiceException("Проект не существует.", 101);
        }
    }

    /**
     * 
     * Удаляет проекты заданного пользователя
     * @param int $userID Идентификатор пользователя
     * @param array $projectsList Список проектов на удаление (ключ - идентификатор)
     */
    public function deleteProjectsFromList($userID, $projectsList) {
        $userID = (int) $userID;
        if ($projectsList != null) {
            $projectListSerialized = Serialize::SerializeForStoredProcedure($projectsList);
            $query = 'CALL DeleteProjects :userId, :projectsList';
            $deleteCommand = $this->db->createCommand($query);
            $deleteCommand->bindParam(':userId', $userID);
            $deleteCommand->bindParam(':projectsList', $projectListSerialized);
            $deleteCommand->execute();
        }
    }

    /**
     * Получение списка всех проектов. Создано 28.01.2011.
     * @param int $startIndex - индекс с которого нужно показывать результаты поиска.
     * @param int $maxCount - максимальное количество результатов поиска.
     */
    public function getAll() {
        $this->_sql->selFields(self::TABLE_PROJ, "PROJ_ID", "PROJ_NM", "DESCR", "USER_ID");
        $ret = $this->_sql->getResultRows();
        return $ret;
    }

    /**
     * 
     * Возвращает проект по его идентификатору
     * @param int $projectID
     */
    public function getProjectById($projectID) {
        $projectID = (int) $projectID;
        $this->_sql->selAllWhere(self::VIEW_PROJECT_AND_OWNER_NICK, "ProjectID=$projectID");
        $data = $this->_sql->getResultRows();
        return $data[0];
    }

    /**
     * 
     * Возращает проекты по списку идентификаторов
     * @param array $projectIDList
     */
    public function getProjectsByList($projectIDList) {
        $projectsListStatement = Serialize::serializeForINStatement($projectIDList);
        if ($projectsListStatement != '') {
            $this->_sql->selAllWhere(self::VIEW_PROJECT_AND_OWNER_NICK, "ProjectID IN $projectsListStatement");
            return $this->_sql->getResultRows();
        } else {
            return null;
        }
    }

    /**
     * 
     * Возращает проекты по списку идентификаторов
     * @param array $projectIDList
     */
    public function getProjectsByListWithSubscribes($userID, $projectIDList) {
        $projectsListStatement = Serialize::serializeForINStatement($projectIDList);
        if ($projectsListStatement != '') {
            $userID = (int) $userID;
            $query = sprintf('SELECT 
    								`P`.*,
    								CASE 
        								WHEN `P`.OwnerID=%1$d THEN 2
        								WHEN `UP`.UserID IS null THEN 0
        								ELSE 1
    								END AS ProjectRelation
								FROM 
    								`projectswithusername` `P`
    							LEFT JOIN USER_IN_PROJ UP ON
        							`P`.ProjectID=`UP`.ProjectID AND `UP`.UserID=%1$d
								WHERE `P`.ProjectID IN %2$s', $userID, $projectsListStatement);
            return $this->_sql->GetRows();
        } else {
            return null;
        }
    }

    public function searchProjectsUsingLikeCount($userID, $query) {
        $query = addslashes($query);
        return $this->_sql->countQuery(self::TABLE_PROJ, "`PROJ_NM` LIKE '%$query%' OR DESCR LIKE '%$query%'");
    }

    public function searchProjectsUsingLike($userID, $query, ListPager $paginator) {
        $userID = (int) $userID;
        //$this->_sql->setLimit($paginator->getOffset(), $paginator->getSize());
        $query = addslashes($query);
        $query = sprintf('SELECT 
							    P.*,
							    CASE 
							    	WHEN P.USER_ID=%1$d THEN 2
							    	WHEN UP.USER_ID IS null THEN 0
							    	ELSE 1
							    END AS ProjectRelation,
                                U.NICK AS NickName
							FROM 
							    %5$s P
							    LEFT JOIN USER_IN_PROJ UP 
                                    ON P.PROJ_ID=UP.PROJ_ID AND UP.USER_ID=%1$d
                                LEFT JOIN USER U
                                    ON U.USER_ID=P.USER_ID
							WHERE 
							    PROJ_NM LIKE \'%%%2$s%%\' OR DESCR LIKE \'%%%2$s%%\'
							LIMIT %3$d,%4$d'
                , $userID, $query, $paginator->getOffset(), $paginator->getSize(), self::TABLE_PROJ);
        $this->_sql->query($query);
        return $this->_sql->GetRows();
    }

    public function getUserProjectsInfo($userId, MyProjectsFieldsENUM $orderField, MySQLOrderEnum $direction, $page = 1, $size = 10) {
        $userId = (int) $userId;
        $this->_sql->setLimit($page, $size);
        $this->_sql->setOrder($orderField, $direction);
        $resource = $this->_sql->selAllWhere(self::VIEW_PROJECT_AND_ERRORS, "ProjectOwnerID=$userId");
        $this->_sql->clearLimit();
        $this->_sql->clearOrder();
        return $this->_sql->getResultRows();
    }

    public function countUserProjectsInfo($userId) {
        $userId = (int) $userId;
        return $this->_sql->countQuery(self::VIEW_PROJECT_AND_ERRORS, "ProjectOwnerID=$userId");
    }

    public function getMemberProjects($userId, MyProjectsFieldsENUM $orderField, MySQLOrderEnum $direction, $page = 1, $size = 10) {
        $userId = (int) $userId;
        $this->_sql->setLimit($page, $size);
        $this->_sql->setOrder($orderField, $direction);
        $resource = $this->_sql->selAllWhere(self::VIEW_PROJECT_INFO_WITHOUT_OWNER, "UserID=$userId");
        $this->_sql->clearLimit();
        $this->_sql->clearOrder();
        return $this->_sql->getResultRows();
    }

    public function countMemberProjects($userId) {
        $userId = (int) $userId;
        return $this->_sql->countQuery(self::VIEW_PROJECT_INFO_WITHOUT_OWNER, "UserID=$userId");
    }

    public function getProjectUsersInfo($projectID) {
        $projectID = (int) $projectID;
        $this->_sql->selAllWhere(self::VIEW_USER_IN_PROJ_ERROR_AND_COMM, "ProjectID=$projectID");
        return $this->_sql->getResultRows();
    }

    /**
     * Получить число участников в проекте
     * 
     * @param int $projectID Идентификатор проекта
     * @return int Количетво участников в проекте 
     */
    public function getProjectUsersInfoCount($projectID) {
        $projectID = (int) $projectID;
        return $this->_sql->countQuery(self::VIEW_USER_IN_PROJ_ERROR_AND_COMM, "ProjectID=$projectID");
    }

    public function getProjectsUsersInfoPagOrd($projectID, ProjectFieldsUsersInfoENUM $orderField, MySQLOrderEnum $direction, $page = 1, $size = 15) {
        $this->_sql->setLimit($from, $size);
        $this->_sql->setOrder($orderField, $direction);
        $res = $this->getProjectUsersInfo($projectID);
        $this->_sql->clearLimit();
        $this->_sql->clearOrder();
        return $res;
    }

    public function getUserProjects($userId) {
        $userId = (int) $userId;
        $this->_sql->selFieldsWhere(self::VIEW_ALL_USER_PROJECTS, "UserID=$userId", 'ProjectID', 'Name');
        return $this->_sql->getResultRows();
    }

    /**
     * 
     * Получить всех участников проекта (и владельца).
     * @param int $projectID
     */
    public function getProjectUsers($projectID) {
        $projectID = (int) $projectID;
        $this->_sql->setOrder(
                new ProjectFieldsUsersInfoENUM(ProjectFieldsUsersInfoENUM::NICK_NAME), new MySQLOrderENUM(MySQLOrderENUM::ASC)
        );
        $this->_sql->selFieldsWhere(self::VIEW_ALL_USER_PROJECTS, "ProjectID=$projectID", 'UserID', 'NickName');
        $this->_sql->clearOrder();
        return $this->_sql->getResultRows();
    }

    /**
     * Получение списка проектов по фильтру.
     * @param string $sortType - фильтр поиска. "date", "name".
     * @param bool $ask - по возрастанию или по убыванию.
     * @param int $startIndex - вывод резултатов с данной позиции.
     * @param int $maxCount - количество результатов вывода.
     */
    public function getSortedProjects($sortType = "date", $ask = true, $startIndex = 0, $maxCount = 20) {
        $startIndex = (int) $startIndex;
        $maxCount = (int) $maxCount;
        switch ($sortType) {
            case "date":
                $sorting = "CreateDate";
                $type = $ask ? "ASC" : "DESC";
                break;

            case "name":
                $sorting = "Name";
                $type = $ask ? "ASC" : "DESC";
                break;

            default:
                return FALSE;
                break;
        }
        $q = "SELECT * FROM `Projects` ORDER BY `$sorting` $type LIMIT $startIndex, $maxCount";
        //die($q);
        $res = $this->_sql->query($q);
        $ret = $this->_sql->GetRows($res);
        return $ret;
    }

    /**
     * Проверка существования проекта.
     * @param int $projectID - id проекта.
     */
    public function isProjectExists($projectID) {
        $projectID = (int) $projectID;
        $this->_sql->selAllWhere(self::TABLE_PROJ, "PROJ_ID = $projectID");
        $tmp = $this->_sql->getResultRows();
        return $tmp == null ? FALSE : TRUE;
    }

    public function getOwnerID($projectID) {
        $projectID = (int) $projectID;
        $this->_sql->selFieldsWhereA(self::TABLE_PROJ, array('USER_ID'), "PROJ_ID = $projectID");
        $tmp = $this->_sql->getResultRows();
        $tmp = $tmp[0];
        return (int) $tmp["USER_ID"];
    }

}

?>