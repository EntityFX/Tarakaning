<?php

/**
 * Класс управления проектами.
 * @author timur 27.01.2011
 *
 */
class ProjectService extends ServiceBase implements IProjectService {

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
                ProjectTable::NAME, array(
                    ProjectTable::PROJ_NM_FIELD => htmlspecialchars($projectName),
                    ProjectTable::DESCR_FIELD => htmlspecialchars($description),
                    ProjectTable::USER_ID_FIELD => (int) $userID,
                    ProjectTable::CRT_TM_FIELD => date("c")
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
                        ->select(ProjectTable::PROJ_NM_FIELD)
                        ->from(ProjectTable::NAME)
                        ->where(
                                ProjectTable::PROJ_NM_FIELD . ' = :projectName', 
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
                                ProjectTable::NAME, 
                                array(
                                    ProjectTable::PROJ_NM_FIELD  => htmlspecialchars($projectNewName),
                                    ProjectTable::DESCR_FIELD    => htmlspecialchars($newDescription),
                                    ProjectTable::PROJ_ID_FIELD  => $projectID,
                                    ProjectTable::USER_ID_FIELD  => $userID
                                ),
                                array(
                                    'and',
                                    ProjectTable::PROJ_ID_FIELD . ' = :projectId',
                                    ProjectTable::USER_ID_FIELD . ' = :userId'
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
                        ->select(ProjectTable::USER_ID_FIELD)
                        ->from(ProjectTable::NAME)
                        ->where(
                                ProjectTable::PROJ_ID_FIELD . '=:projectId', 
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
                                ProjectTable::NAME, 
                                ProjectTable::PROJ_ID_FIELD . '=:projectId', 
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
                ->select(
                        array(
                            ProjectTable::PROJ_ID_FIELD, 
                            ProjectTable::PROJ_NM_FIELD, 
                            ProjectTable::DESCR_FIELD, 
                            ProjectTable::USER_ID_FIELD
                        )
                )
                ->from(ProjectTable::NAME)
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
                ->from(ProjectAndOwnerNickView::NAME)
                ->where(
                        ProjectAndOwnerNickView::PROJECT_ID_FIELD . '=:projectID',
                        array(':projectID' => (int) $projectID)
                )
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
                ->from(ProjectAndOwnerNickView::NAME)
                ->where(array('in',ProjectAndOwnerNickView::PROJECT_ID_FIELD,$projectIDList))
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
            $query =    "SELECT 
                            `P`.*,
                            CASE 
                                    WHEN `P`.OwnerID=:ownerId THEN 2
                                    WHEN `UP`.USER_ID IS null THEN 0
                                    ELSE 1
                            END AS ProjectRelation
                        FROM 
                                " . ProjectAndOwnerNickView::NAME . " `P`
                            LEFT JOIN " . UserInProjectTable::NAME . " UP ON
                                `P`." . ProjectAndOwnerNickView::PROJECT_ID_FIELD . "=`UP`.PROJ_ID 
                                AND `UP`." .UserInProjectTable::USER_ID_FIELD . "=:ownerId
                        WHERE 
                            `P`." . ProjectAndOwnerNickView::PROJECT_ID_FIELD . " IN $projectsListStatement";
            $selectCommand = $this->db->createCommand($query);
            $selectCommand->bindParam(':ownerId', $userID);
            return $selectCommand->queryAll();
        } else {
            return null;
        }
    }

    public function searchProjectsUsingLikeCount($pattern) {
        $pattern = addslashes($pattern) . "%";
        return $this->getCount(ProjectTable::NAME, array(
                    'or',
                    ProjectTable::PROJ_NM_FIELD . ' LIKE :query',
                    ProjectTable::DESCR_FIELD . ' LIKE :query'
                ), 
                array(
                    ':query' => $pattern
                )
        );
    }

    public function searchProjectsUsingLike($userID, $pattern, $page = 0 , $size = 10) {
        $userID = (int) $userID;
        $pattern = addslashes($pattern) . "%";
        $userID = (int) $userID;
        $query =    "SELECT 
                        P.*,
                        CASE 
                            WHEN P." . ProjectTable::USER_ID_FIELD . "=:userId THEN 2
                            WHEN UP." . UserInProjectTable::USER_ID_FIELD . " IS null THEN 0
                            ELSE 1
                        END AS ProjectRelation,
                        U." . UserTable::NICK_FIELD . " AS NickName
                    FROM 
                        " . ProjectTable::NAME . " P
                        LEFT JOIN " . UserInProjectTable::NAME . " UP 
                            ON P." . ProjectTable::PROJ_ID_FIELD . "=UP. " . UserInProjectTable::PROJ_ID_FIELD . " 
                            AND UP." . UserInProjectTable::USER_ID_FIELD . "=:userId
                        LEFT JOIN " . UserTable::NAME . " U
                            ON U." . UserTable::USER_ID_FIELD . "=P." . ProjectTable::USER_ID_FIELD ."
                    WHERE 
                        " . ProjectTable::PROJ_NM_FIELD . " LIKE :pattern OR DESCR LIKE :pattern
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
                ->from(ProjectAndItemsView::NAME)
                ->where(ProjectAndItemsView::OWNER_ID_FIELD . ' = :userId',array(':userId' => $userId))
                ->order($this->order($orderField,$direction))
                ->limit($size, $page)
                ->queryAll();
    }

    public function getUserProjectsInfoCount($userId) {
        $userId = (int) $userId;
        return $this->getCount(
                ProjectAndItemsView::NAME, 
                ProjectAndItemsView::OWNER_ID_FIELD . ' = :userId', 
                array(
                    ':userId' => $userId
                )
            );
    }

    public function getMemberProjects($userId, MyProjectsFieldsENUM $orderField, MySQLOrderEnum $direction, $page = 0, $size = 10) {
        $userId = (int) $userId;
        return $this->db->createCommand()
                ->select()
                ->from(ProjectInfoWithoutOwnerView::NAME)
                ->where(ProjectInfoWithoutOwnerView::USER_ID_FIELD . ' = :userId',array(':userId' => $userId))
                ->order($this->order($orderField,$direction))
                ->limit($size,$page)
                ->queryAll();
    }

    public function getMemberProjectsCount($userId) {
        $userId = (int) $userId;
        return $this->getCount(
                ProjectInfoWithoutOwnerView::NAME, 
                ProjectInfoWithoutOwnerView::USER_ID_FIELD . ' = :userId', 
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
                ->from(UserInProjectErrorsAndComments::NAME)
                ->where(UserInProjectErrorsAndComments::PROJECT_ID_FIELD . ' = :projectId',array(':projectId' => $projectID))
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
                serInProjectErrorsAndComments::NAME, 
                UserInProjectErrorsAndComments::PROJECT_ID_FIELD . ' = :projectId', 
                array(':projectId' => $projectID)
        );
    }

    public function getProjectsUsersInfoPagOrd($projectID, ProjectFieldsUsersInfoENUM $orderField, MySQLOrderEnum $direction, $page = 0, $size = 15) {
        return $this->db->createCommand()
                ->select()
                ->from(UserInProjectErrorsAndComments::NAME)
                ->where(
                        UserInProjectErrorsAndComments::PROJECT_ID_FIELD . ' = :projectId',
                        array(':projectId' => (int)$projectID)
                )
                ->order($this->order($orderField,$direction))
                ->limit($size,$page)
                ->queryAll();
    }

    public function getUserProjects($userId) {
        $userId = (int) $userId;
        return $this->db->createCommand()
                ->select(array(AllUserProjectsView::PROJECT_ID_FIELD, AllUserProjectsView::NAME_FIELD))
                ->from(AllUserProjectsView::NAME)
                ->where(AllUserProjectsView::USER_ID_FIELD . ' = :userId',array(':userId' => $userId))
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
                ->from(UserInProjectErrorsAndComments::NAME)
                ->where(UserInProjectErrorsAndComments::PROJECT_ID_FIELD . ' = :projectId',array(':projectId' => $projectID))
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
                $sorting = ProjectTable::CRT_TM_FIELD;
                break;

            case "name":
                $sorting = ProjectTable::PROJ_NM_FIELD;
                break;

            default:
                return FALSE;
                break;
        }
        $type = $ask ? "ASC" : "DESC";
        return $this->db->createCommand()
                ->select()
                ->from(ProjectTable::NAME)
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
                ->select(ProjectTable::PROJ_ID_FIELD)
                ->from(ProjectTable::NAME)
                ->where(ProjectTable::PROJ_ID_FIELD . ' = :projectId',array(':projectId' => $projectID))
                ->queryScalar() !== false;
    }

    public function getOwnerID($projectID) {
        $projectID = (int) $projectID;
        return (int)$this->db->createCommand()
                ->select(ProjectTable::USER_ID_FIELD)
                ->from(ProjectTable::NAME)
                ->where(ProjectTable::PROJ_ID_FIELD . ' = :projectId',array(':projectId' => $projectID))
                ->queryScalar();
    }

}

?>