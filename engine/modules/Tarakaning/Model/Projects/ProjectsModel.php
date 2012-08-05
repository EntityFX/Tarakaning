<?php

/**
 * ����� ���������� ���������.
 * @author timur 27.01.2011
 *
 */
class ProjectsModel extends DBConnector {

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
     * ���������� ������ �������. ������� 27.01.2011.
     * @param $userId - id ������������, ���������� ������.
     * @param $projectName - �������� �������.
     * @param $description - �������� �������.
     * @return bool ��������� ��������. 
     * 
     * @todo 1) �������� ������������� ������������.<br />
     * 2) ��� ���������� ������� ������ ����������� ���������� � ������� �������.
     */
    public function addProject($userID, $projectName, $description) {
        if (trim($projectName) == '') {
            throw new Exception("��������� ������� �� ������ ���� ������");
        }
        $userID = (int) $userID;
        $this->_sql->insert(
                self::TABLE_PROJ, new ArrayObject(array(
                    $projectName,
                    $description,
                    $userID,
                    date("c")
                )), new ArrayObject(array(
                    'PROJ_NM',
                    'DESCR',
                    'USER_ID',
                    'CRT_TM'
                ))
        );
        return $this->_sql->getLastID();
    }

    /**
     * ��������� ���������� �� ������ � ������������ ���������.
     * @param unknown_type $description
     */
    public function isExistThisProjectName($description) {
        $res = $this->_sql->query("SELECT * FROM `Projects` WHERE `Name`='$description'");
        $ret = $this->_sql->fetchArr($res);
        if ($ret != null)
            return TRUE; //throw new Exception("������ � ����� ������ ��� ����������.", 103); 
    }

    /**
     * ���������� ����� �������. �������� ��� ����� ������ ��������� �������. ������� 28.01.2011.
     * @param int $userID - id ������������, ���������� ������.
     * @param string $projectNewName - ����� �������� �������.
     * @param int $projectID - id �������, ����������� ��������� ��������.
     * @return bool - ��������� ����������.
     * 
     * @todo ��� ��������� �������� ������� ����������� ������: <br /> 
     * ���������� � ������� ������� ������� (-) <br />
     * � ���������� � ������� �������� (+) <br />
     */
    public function setProjectName($projectID, $userID, $projectNewName, $newDescription) {
        $userID = (int) $userID;
        $projectID = (int) $projectID;
        if ($this->isProjectExists($projectID)) {
            if ($projectNewName == '') {
                throw new Exception("��� ������� �� ������ ���� ������");
            }
            if ($this->isOwner($userID, $projectID)) {
                $this->_sql->update(
                        self::TABLE_PROJ, "PROJ_ID = $projectID AND USER_ID = $userID", new ArrayObject(array(
                            'PROJ_NM' => $projectNewName,
                            'DESCR' => $newDescription
                        ))
                );
            } else {
                throw new Exception("�� �� ��������� ���������� �������.", 102);
            }
        } else {
            throw new Exception("������ �� ����������.", 101);
        }
    }

    /**
     * ����������� �������� �� ������������ ������� �������.
     * @param int $userID - id ������������, ���������� ������.
     * @param int $projectID - id �������, ����������� ��������� ��������.
     */
    public function isOwner($userID, $projectID) {
        $userID = (int) $userID;
        $projectID = (int) $projectID;
        $this->_sql->selFieldsWhere(self::TABLE_PROJ, "PROJ_ID = '$projectID'", "USER_ID");
        $rec = $this->_sql->getResultRows();
        $rec = $rec[0];
        return ($userID != (int) $rec["USER_ID"]) ? false : true;
    }

    /**
     * �������� �������. ������� ������ ����� ������ ���������. ������� 28.01.2011.
     * @param int $userID
     * @param int $projectID
     * 
     * @return bool - ��������� ����������
     * 
     * @todo �������� �������� ������������ - �������� �� ���������������. ����� ����� ����� � ��� �������.
     */
    public function deleteProject($userID, $projectID) {
        $userID = (int) $userID;
        $projectID = (int) $projectID;
        if ($this->isProjectExists($projectID)) {
            if ($this->isOwner($userID, $projectID)) {
                return $this->_sql->delete(
                                self::TABLE_PROJ, ProjectFieldsENUM::PROJECT_ID . " = $projectID AND " . ProjectFieldsENUM::USER_ID . " = $userID"
                );
            } else {
                throw new Exception("�� �� ��������� ���������� �������.", 102);
            }
        } else {
            throw new Exception("������ �� ����������.", 101);
        }
    }

    /**
     * 
     * ������� ������� ��������� ������������
     * @param int $userID ������������� ������������
     * @param array $projectsList ������ �������� �� �������� (���� - �������������)
     */
    public function deleteProjectsFromList($userID, $projectsList) {
        $userID = (int) $userID;
        if ($projectsList != null) {
            $projectListSerialized = Serialize::SerializeForStoredProcedure($projectsList);
            $this->_sql->call(
                    'DeleteProjects', new ArrayObject(array(
                        $userID,
                        $projectListSerialized
                    ))
            );
        }
    }

    /**
     * ��������� ������ ���� ��������. ������� 28.01.2011.
     * @param int $startIndex - ������ � �������� ����� ���������� ���������� ������.
     * @param int $maxCount - ������������ ���������� ����������� ������.
     */
    public function getProjects() {
        $this->_sql->selFields(self::TABLE_PROJ, "PROJ_ID", "PROJ_NM", "DESCR", "USER_ID");
        $ret = $this->_sql->getResultRows();
        return $ret;
    }

    /**
     * 
     * ���������� ������ �� ��� ��������������
     * @param int $projectID
     */
    public function getProjectById($projectID) {
        $projectID = (int) $projectID;
        $this->_sql->selAllWhere(self::VIEW_PROJECT_AND_OWNER_NICK, "ProjectID=$projectID");
        $data = $this->_sql->getResultRows();
        return $data[0];
    }

    public function getNextByUserID($projectID, $userID) {
        $projectID = (int) $projectID;
        $userID = (int) $userID;
        $this->_sql->setLimit(0, 1);
        $this->_sql->setOrder(new ProjectFieldsENUM(ProjectFieldsENUM::PROJECT_NAME), new MySQLOrderENUM(MySQLOrderENUM::ASC));
        $this->_sql->selFieldsWhere(self::TABLE_PROJ, "PROJ_ID > $projectID AND USER_ID = $userID", "PROJ_ID");
        $this->_sql->clearOrder();
        $this->_sql->clearLimit();
        $result = $this->_sql->getResultRows();
        return (int) $result[0]["PROJ_ID"];
    }

    /**
     * 
     * ��������� ������� �� ������ ���������������
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
     * ��������� ������� �� ������ ���������������
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
     * �������� ����� ���������� � �������
     * 
     * @param int $projectID ������������� �������
     * @return int ��������� ���������� � ������� 
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
     * �������� ���� ���������� ������� (� ���������).
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
     * ��������� ������ �������� �� �������.
     * @param string $sortType - ������ ������. "date", "name".
     * @param bool $ask - �� ����������� ��� �� ��������.
     * @param int $startIndex - ����� ���������� � ������ �������.
     * @param int $maxCount - ���������� ����������� ������.
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
     * �������� ������������� �������.
     * @param int $projectID - id �������.
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