<?php

/**
 * Класс управления проектами.
 * @author timur 27.01.2011
 *
 */
class ProjectService extends ServiceBase implements IProjectService {

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
        return $this->db->createCommand()
                        ->select('PROJ_NM')
                        ->from(self::TABLE_PROJ)
                        ->where(
                                'PROJ_NM = :projectName', 
                                array(
                                    ':projectName' => $name
                                )
                        )
                        ->queryScalar() !== false;
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
        if ($this->existsById($projectID)) {
            if ($projectNewName == '') {
                throw new ServiceException("Имя проекта не должно быть пустым");
            }
            if ($this->isOwner($userID, $projectID)) {
                $this->db->createCommand()
                        ->update(
                                self::TABLE_PROJ, 
                                array(
                                    'PROJ_NM' => htmlspecialchars($projectNewName),
                                    'DESCR'   => htmlspecialchars($newDescription),
                                    'PROJ_ID' => $projectID,
                                    'USER_ID' => $userID
                                ),
                                array(
                                    'and',
                                    'PROJ_ID = :projectId',
                                    'USER_ID = :userId'
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
        if ($this->existsById($projectID)) {
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
            $projectListSerialized = SerializeHelper::SerializeForStoredProcedure($projectsList);
            $query = 'CALL DeleteProjects(:userId, :projectsList)';
            $deleteCommand = $this->db->createCommand($query);
            $deleteCommand->bindParam(':userId', $userID);
            $deleteCommand->bindParam(':projectsList', $projectListSerialized);
            $deleteCommand->execute();
        }
    }

    /**
     * Получение списка всех проектов. Создано 28.01.2011.
     * 
     * @param int $startIndex - индекс с которого нужно показывать результаты поиска.
     * @param int $maxCount - максимальное количество результатов поиска.
     * @return array 
     */
    public function getAll() {
        return $this->db->createCommand()
                ->select(array("PROJ_ID", "PROJ_NM", "DESCR", "USER_ID"))
                ->from(self::TABLE_PROJ)
                ->queryAll();
    }

    /**
     * Возвращает проект по его идентификатору
     * 
     * @param int $projectID
     * @return array
     */
    public function getProjectById($projectID) {
        return $this->db->createCommand()
                ->select()
                ->from(self::VIEW_PROJECT_AND_OWNER_NICK)
                ->where('ProjectID=:projectID',array(':projectID' => (int) $projectID))
                ->queryRow();
    }

    /**
     * 
     * Возращает проекты по списку идентификаторов
     * @param array $projectIDList
     */
    public function getProjectsByList($projectIDList) {
        $projectsListStatement = SerializeHelper::serializeForINStatement($projectIDList);
        if ($projectsListStatement != '') {
        return $this->db->createCommand()
                ->select()
                ->from(self::VIEW_PROJECT_AND_OWNER_NICK)
                ->where(array('in','ProjectID',$projectIDList))
                ->queryAll();
        } else {
            return null;
        }
    }

    /**
     * 
     * Возращает проекты по списку идентификаторов
     * @param array $projectIDList
     */
    public function getProjectsWithSubscribesByList($userID, $projectIDList) {
        $projectsListStatement = SerializeHelper::serializeForINStatement($projectIDList);
        if ($projectsListStatement != '') {
            $userID = (int) $userID;
            $table = self::VIEW_PROJECT_AND_OWNER_NICK;
            $query =    "SELECT 
                            `P`.*,
                            CASE 
                                    WHEN `P`.OwnerID=:ownerId THEN 2
                                    WHEN `UP`.USER_ID IS null THEN 0
                                    ELSE 1
                            END AS ProjectRelation
                        FROM 
                                $table `P`
                        LEFT JOIN USER_IN_PROJ UP ON
                                `P`.ProjectID=`UP`.PROJ_ID AND `UP`.USER_ID=:ownerId
                        WHERE 
                                `P`.ProjectID IN $projectsListStatement";
            $selectCommand = $this->db->createCommand($query);
            $selectCommand->bindParam(':ownerId', $userID);
            return $selectCommand->queryAll();
        } else {
            return null;
        }
    }

    public function searchProjectsUsingLikeCount($pattern) {
        $pattern = addslashes($pattern) . "%";
        return $this->getCount(self::TABLE_PROJ, array(
                    'or',
                    'PROJ_NM LIKE :query',
                    'DESCR LIKE :query'
                        ), array(
                    ':query' => $pattern
                ));
    }

    public function searchProjectsUsingLike($userID, $pattern, $page = 0 , $size = 10) {
        $userID = (int) $userID;
        $pattern = addslashes($pattern) . "%";
        $userID = (int) $userID;
        $table = self::TABLE_PROJ;
        $query =    "SELECT 
                        P.*,
                        CASE 
                            WHEN P.USER_ID=:userId THEN 2
                            WHEN UP.USER_ID IS null THEN 0
                            ELSE 1
                        END AS ProjectRelation,
                        U.NICK AS NickName
                    FROM 
                        $table P
                        LEFT JOIN USER_IN_PROJ UP 
                            ON P.PROJ_ID=UP.PROJ_ID AND UP.USER_ID=:userId
                        LEFT JOIN USER U
                            ON U.USER_ID=P.USER_ID
                    WHERE 
                        PROJ_NM LIKE :pattern OR DESCR LIKE :pattern
                    LIMIT :offset,:size";
        $selectCommand = $this->db->createCommand($query);
        $selectCommand->bindParam(':userId', $userID);
        $selectCommand->bindParam(':offset', $page);
        $selectCommand->bindParam(':size', $size);
        $selectCommand->bindParam(':pattern', $pattern);
        return $selectCommand->queryAll();
    }

    public function getUserProjectsInfo($userId, MyProjectsFieldsENUM $orderField, MySQLOrderEnum $direction, $page = 0, $size = 10) {
        $userId = (int) $userId;
        return $this->db->createCommand()
                ->select()
                ->from(self::VIEW_PROJECT_AND_ERRORS)
                ->where('ProjectOwnerID = :userId',array(':userId' => $userId))
                ->order($this->order($orderField,$direction))
                ->limit($size, $page)
                ->queryAll();
    }

    public function getUserProjectsInfoCount($userId) {
        $userId = (int) $userId;
        return $this->getCount(
                self::VIEW_PROJECT_AND_ERRORS, 
                'ProjectOwnerID = :userId', 
                array(
                    ':userId' => $userId
                )
            );
    }

    public function getMemberProjects($userId, MyProjectsFieldsENUM $orderField, MySQLOrderEnum $direction, $page = 0, $size = 10) {
        $userId = (int) $userId;
        return $this->db->createCommand()
                ->select()
                ->from(self::VIEW_PROJECT_INFO_WITHOUT_OWNER)
                ->where('UserID = :userId',array(':userId' => $userId))
                ->order($this->order($orderField,$direction))
                ->limit($size,$page)
                ->queryAll();
    }

    public function getMemberProjectsCount($userId) {
        $userId = (int) $userId;
        return $this->getCount(
                self::VIEW_PROJECT_INFO_WITHOUT_OWNER, 
                'UserID = :userId', 
                array(':userId' => $userId)
        );
    }

    /**
     * Returns total information about users
     * 
     * @param int $projectID Project identificator
     * @return array Information list about users 
     */
    public function getProjectUsersInfo($projectID) {
        $projectID = (int) $projectID;
        return $this->db->createCommand()
                ->select()
                ->from(self::VIEW_USER_IN_PROJ_ERROR_AND_COMM)
                ->where('ProjectID = :projectId',array(':projectId' => $projectID))
                ->queryAll();
    }

    /**
     * Получить число участников в проекте
     * 
     * @param int $projectID Идентификатор проекта
     * @return int Количетво участников в проекте 
     */
    public function getProjectUsersInfoCount($projectID) {
        $projectID = (int) $projectID;
        return $this->getCount(
                self::VIEW_USER_IN_PROJ_ERROR_AND_COMM, 
                'ProjectID = :projectId', 
                array(':projectId' => $projectID)
        );
    }

    public function getProjectsUsersInfoPagOrd($projectID, ProjectFieldsUsersInfoENUM $orderField, MySQLOrderEnum $direction, $page = 0, $size = 15) {
        return $this->db->createCommand()
                ->select()
                ->from(self::VIEW_USER_IN_PROJ_ERROR_AND_COMM)
                ->where('ProjectID = :projectId',array(':projectId' => (int)$projectID))
                ->order($this->order($orderField,$direction))
                ->limit($size,$page)
                ->queryAll();
    }

    public function getUserProjects($userId) {
        $userId = (int) $userId;
        return $this->db->createCommand()
                ->select(array('ProjectID', 'Name'))
                ->from(self::VIEW_ALL_USER_PROJECTS)
                ->where('UserID = :userId',array(':userId' => $userId))
                ->queryAll();
    }

    /**
     * 
     * Получить всех участников проекта (и владельца).
     * @param int $projectID
     */
    public function getProjectUsers($projectID) {
        $projectID = (int) $projectID;
        return $this->db->createCommand()
                ->select()
                ->from(self::VIEW_USER_IN_PROJ_ERROR_AND_COMM)
                ->where('ProjectID = :projectId',array(':projectId' => $projectID))
                ->order(
                        $this->order(
                            new ProjectFieldsUsersInfoENUM(ProjectFieldsUsersInfoENUM::NICK_NAME), 
                            new MySQLOrderENUM(MySQLOrderENUM::ASC)
                        )
                )
                ->queryAll();
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
                $sorting = "CRT_TM";
                break;

            case "name":
                $sorting = "PROJ_NM";
                break;

            default:
                return FALSE;
                break;
        }
        $type = $ask ? "ASC" : "DESC";
        return $this->db->createCommand()
                ->select()
                ->from(self::TABLE_PROJ)
                ->limit($maxCount, $startIndex)
                ->order($sorting.' '.$type)
                ->queryAll();
    }

    /**
     * Проверка существования проекта.
     * @param int $projectID - id проекта.
     */
    public function existsById($projectID) {
        $projectID = (int) $projectID;
        return $this->db->createCommand()
                ->select('PROJ_ID')
                ->from(self::TABLE_PROJ)
                ->where('PROJ_ID = :projectId',array(':projectId' => $projectID))
                ->queryScalar() !== false;
    }

    public function getOwnerID($projectID) {
        $projectID = (int) $projectID;
        return (int)$this->db->createCommand()
                ->select('USER_ID')
                ->from(self::TABLE_PROJ)
                ->where('PROJ_ID = :projectId',array(':projectId' => $projectID))
                ->queryScalar();
    }

}

?>