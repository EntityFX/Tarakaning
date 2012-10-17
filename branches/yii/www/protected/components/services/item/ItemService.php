<?php

class ItemService extends ServiceBase {

    const VIEW_ITEM_FULL_INFO = 'view_ItemFullInfo';
    const TABLE_ITEM = 'ITEM';

    private $_defaultUserId;
    private $_defaultProjectId;

    public function __construct($projectId = null, $userId = null) {
        parent::__construct();

        if ($projectId != null) {
            $this->setDefaultProjectId($projectId);
        }

        if ($userId !== null) {
            $this->setDefaultUserId($userId);
        }

        if ($this->_defaultUserId !== null
                && $this->_defaultUserId != null) {
            $this->tryCheckIsSubscribedOrOwner();
        }
    }

    private function isMember() {
        $subscribeService = new SubscribeService();
        $projectService = new ProjectService();
        return $subscribeService->isSubscribed($this->_defaultUserId, $this->_defaultProjectId)
                || $this->_defaultUserId == $projectService->getOwnerID($this->_defaultProjectId);
    }

    private function tryCheckIsSubscribedOrOwner() {
        if (!$this->isMember()) {
            throw new ServiceException("Пользователь №" . $this->_defaultUserId . " не подписан на проект $this->_defaultProjectId или не является его владельцем");
        }
    }

    public function setDefaultUserId($userId) {
        $userId = (int) $userId;
        $userService = new UserService();
        if ($userService->existsById($userId)) {
            $this->_defaultUserId = (int) $userId;
        } else {
            throw new ServiceException("Пользователь не существует. Нельзя несуществующему пользователю отставлять отчёт об ошибках");
        }
    }

    public function setDefaultProjectId($projectId) {
        $projectId = (int) $projectId;
        $projectService = new ProjectService();
        if ($projectService->existsById($projectId)) {
            $this->_defaultProjectId = $projectId;
        } else {
            throw new ServiceException("Проект не существует. Нельзя присвоить несуществующему проекту отчёты об ошибках");
        }
    }

    /**
     * @var ItemDBKindENUM тип элемента
     * @var ErrorPriorityENUM приоритет элемента
     * @var ErrorStatusENUM статус элемента
     */
    public function addReport(ItemDBKindENUM $kind, ErrorPriorityENUM $priority, ErrorTypeEnum $type, $title, $hoursRequired, $description = "", $steps = "", $assignedTo = null) {
        $title = htmlspecialchars($title);
        if ($title == "") {
            throw new ServiceException("Заголовок не должен быть пустым");
        }
        if ($kind->check()) {
            $kindValue = (string) $kind->getValue();
        } else {
            throw new ServiceException("Неверный тип ошибки");
        }
        if ($priority->check()) {
            $priorityValue = (string) $priority->getValue();
        } else {
            throw new ServiceException("Неверный приоритет ошибки");
        }
        if ($type->check()) {
            $typeValue = $type->getValue();
        } else {
            throw new ServiceException("Неверный формат ошибки");
        }
        $description = htmlspecialchars($description);
        $steps = htmlspecialchars($steps);
        $hoursRequired = (int) $hoursRequired;
        $assignedTo = $assignedTo == ' ' ? null : (int) $assignedTo;
        $query = 'CALL DeleteProjects(
                    :UserId, 
                    :ProjectId, 
                    :AssignedTo, 
                    :PriorityLevel, 
                    :Status, 
                    :CreateDateTime, 
                    :Title, 
                    :Kind,
                    :Description,
                    :ItemType,
                    :StepsText
                 )';
        $addCommand = $this->db->createCommand($query);
        $addCommand->bindParam(':UserId', $this->_defaultUserId);
        $addCommand->bindParam(':ProjectId', $this->_defaultProjectId);
        $addCommand->bindParam(':AssignedTo', $assignedTo);
        $addCommand->bindParam(':PriorityLevel', $priorityValue);
        $addCommand->bindParam(':Status', new ErrorStatusENUM());
        $addCommand->bindParam(':CreateDateTime', date("Y-m-d H:i:s"));
        $addCommand->bindParam(':Title', $title);
        $addCommand->bindParam(':Kind', $kindValue);
        $addCommand->bindParam(':HoursRequired', $hoursRequired);
        $addCommand->bindParam(':Description', $description);
        $addCommand->bindParam(':ItemType', $typeValue);
        $addCommand->bindParam(':StepsText', $steps);
        $addCommand->execute();
        return $this->_sql->getLastID();
    }

    public function deleteItem($reportID) {
        $id = (int) $reportID;
        $this->db->createCommand()
                ->delete(
                        self::TABLE_ITEM, 'ITEM_ID=:itemId', array(
                    ':itemId' => $id
                        )
        );
    }

    public function deleteReportsFromList($keysList, $userID = null, $projectID = null) {
        $userID = $userID == null ? $this->_defaultUserId : (int) $userID;
        $projectID = $projectID == null ? $this->_defaultProjectId : (int) $projectID;
        if ($keysList != '') {
            $query = 'CALL DeleteItemsFromList(
                        :UserId, 
                        :ProjectId, 
                        :ItemsList, 
                    )';
            $deleteCommand = $this->db->createCommand($query);
            $deleteCommand->bindParam(':UserId', $userID);
            $deleteCommand->bindParam(':ProjectId', $projectID);
            $deleteCommand->bindParam(':ItemsList', $keysList);
            $deleteCommand->execute();
        }
    }

    /**
     *
     * Редактировать статус задачи
     * @param $itemID int ID отчёта
     * @param $errorStatusErrorStatusENUM Новый статус
     * @param $userID int Текущий юзер
     */
    public function editItem($itemID, $userID, $projectID, $title, $hoursRequired, $addHours, ErrorStatusENUM $newStatus, ErrorPriorityENUM $priority, ErrorTypeEnum $type, $description = "", $steps = "", $assignedTo = null) {
        if ($newStatus->check()) {
            $newStatusValue = $newStatus->getValue();
        } else {
            throw new ServiceException("Неверный статус ошибки");
        }
        $report = $this->getReportByID($itemID);
        $hoursRequired = (int) $hoursRequired;
        $addHours = (int) $addHours;
        if ($report != null) {
            $currentStatusValue = $report["Status"];
            $statusesArray = $newStatus->getNumberedKeys();
            $currentValueKey = array_search($currentStatusValue, $statusesArray);
            $newValueKey = array_search($newStatusValue, $statusesArray);
            if ($this->canEditStatus($itemID, $projectID) && ($newValueKey - $currentValueKey) <= 1) {
                $editStatusFlag = false;
                $canEditData = $this->canEditData($itemID, $projectID);
                if ($currentStatusValue != ErrorStatusENUM::CLOSED) {
                    if ($currentStatusValue == ErrorStatusENUM::RESOLVED) {
                        $editStatusFlag = $newStatusValue != ErrorStatusENUM::CLOSED ? true : $canEditData;
                    } else {
                        $editStatusFlag = true;
                    }
                } else if ($userID == $report["UserID"]) {
                    $editStatusFlag = true;
                }
                if ($editStatusFlag) {
                    if (!$canEditData) {
                        if ($currentStatusValue == $newStatusValue)
                            return false;
                    }
                    else {
                        $title = htmlspecialchars($title);
                        $description = htmlspecialchars($description);
                        if ($title == '')
                            throw new ServiceException("Заголовок не должен быть пустым");
                        $query = 'CALL EditItem(
                                    :ItemId, 
                                    :Title, 
                                    :PriorityLevel, 
                                    :StatusValue, 
                                    :AssignedTo, 
                                    :HoursRequired,
                                    :AddHours,
                                    :Description,
                                    :DefectType,
                                    :StepsText
                                )';
                        $editCommand = $this->db->createCommand($query);
                        $editCommand->bindParam(':ItemId', $itemID);
                        $editCommand->bindParam(':Title', $title);
                        $editCommand->bindParam(':PriorityLevel', (int) $priority->getValue());
                        $editCommand->bindParam(':StatusValue', $newStatusValue);
                        $editCommand->bindParam(':AssignedTo', (int) $assignedTo);
                        $editCommand->bindParam(':HoursRequired', $hoursRequired);
                        $editCommand->bindParam(':AddHours', $addHours);
                        $editCommand->bindParam(':Description', $description);
                        $editCommand->bindParam(':DefectType', $type->getValue());
                        $editCommand->bindParam(':StepsText', $steps);
                        $editCommand->execute();
                    }
                    return true;
                }
            }
        }
    }

    /**
     * Получиить владельца отчёта
     *
     * @param int $itemId ID отчёта
     * @return int
     */
    private function getReportOwner($itemId) {
        $itemId = (int) $itemId;
        return (int) $this->db->createCommand()
                        ->select(array("USER_ID"))
                        ->from(self::TABLE_ITEM)
                        ->where('ITEM_ID = :itemId', array(':itemId' => $itemId))
                        ->queryScalar();
    }

    private function isDefaultProjectItem($itemId) {
        $itemId = (int) $itemId;
        $projectID = $this->db->createCommand()
                ->select(array("PROJ_ID"))
                ->from(self::TABLE_ITEM)
                ->where('ITEM_ID = :itemId', array(':itemId' => $itemId))
                ->queryScalar();
        return $projectID == $this->_defaultProjectId;
    }

    public function existsById($itemId) {
        $id = (int) $itemId;
        return $this->db->createCommand()
                        ->select('PROJ_ID')
                        ->from(self::TABLE_ITEM)
                        ->where('ITEM_ID = :itemId', array(':itemId' => $id))
                        ->queryScalar() !== false;
    }

    private function getWhereTokenForItemKind(ItemKindENUM $kind, $projectId) {
        $token = null;
        $itemKind = $kind->getValue();
        if ($itemKind <> ItemKindENUM::ALL) {
            $token = array(
                'where' => array('and', 'ProjectID = :projectId', 'Kind = :kind'),
                'params' => array(
                    ':projectId' => (int) $projectId,
                    ':kind' => $itemKind
                )
            );
        } else {
            $token = array(
                'where' => 'ProjectID = :projectId',
                'params' => array(':projectId' => (int) $projectId)
            );
        }
        return $token;
    }

    public function getReportsByProject($projectID, ItemKindENUM $kind, $from, $size) {
        $this->tryCheckProject($projectID);
        $this->useLimit($from, $size);
        $token = $this->getWhereTokenForItemKind($kind, $projectID);
        $res = $this->db->createCommand()
                ->select('PROJ_ID')
                ->from(self::VIEW_ITEM_FULL_INFO)
                ->where($token['where'], $token['params'])
                ->queryAll();
        if ($res != null) {
            foreach ($res as $index => $report) {
                $this->normalizeBugReport(&$report);
                $res[$index] = $report;
            }
        }
        return $res;
    }

    public function countReportsByProject($projectID, ItemKindENUM $kind) {
        $this->tryCheckProject($projectID);
        $token = $this->getWhereTokenForItemKind($kind, $projectID);
        return $this->getCount(
                        self::VIEW_ITEM_FULL_INFO, $token['where'], $token['params']
        );
    }

    private function tryCheckProject(&$projectID) {
        if ($projectID == NULL) {
            $projectID = $this->_defaultProjectId;
        } else {
            $pc = new ProjectService();
            if ($pc->existsById((int) $projectID)) {
                $projectID = (int) $projectID;
            } else {
                throw new ServiceException("Проект не существует. Нельзя получить список ошибок по несуществующему проекту");
            }
        }
    }

    public function countReports(ItemKindENUM $kind) {
        $userID = $this->_defaultUserId;
        $projectID = $this->_defaultProjectId;
        $itemKind = $kind->getValue();
        if ($itemKind <> ItemKindENUM::ALL) {
            $token = array(
                'where' => array(
                    'and', 
                    'ProjectID = :projectId', 
                    'Kind = :kind',
                    'UserID = :userId'
                ),
                'params' => array(
                    ':projectId' => (int) $projectID,
                    ':kind' => $itemKind,
                    ':userId' => $userID
                )
            );
        } else {
            $token = array(
                'where' => array(
                    'and', 
                    'ProjectID = :projectId', 
                    'UserID = :userId'
                ),
                'params' => array(
                    ':projectId' => (int) $projectID,
                    ':userId' => $userID
                )
            );
        }
        return $this->getCount(
                        self::VIEW_ITEM_FULL_INFO, $token['where'], $token['params']
        );
    }

    public function countAssignedReports(ItemKindENUM $kind) {
        $userID = $this->_defaultUserId;
        $projectID = $this->_defaultProjectId;
        $itemKind = $kind->getValue();
        if ($itemKind <> ItemKindENUM::ALL) {
            $token = array(
                'where' => array(
                    'and', 
                    'ProjectID = :projectId', 
                    'Kind = :kind',
                    'AssignedTo = :userId'
                ),
                'params' => array(
                    ':projectId' => (int) $projectID,
                    ':kind' => $itemKind,
                    ':userId' => $userID
                )
            );
        } else {
            $token = array(
                'where' => array(
                    'and', 
                    'ProjectID = :projectId', 
                    'AssignedTo = :userId'
                ),
                'params' => array(
                    ':projectId' => (int) $projectID,
                    ':userId' => $userID
                )
            );
        }
        return $this->getCount(
                        self::VIEW_ITEM_FULL_INFO, $token['where'], $token['params']
        );
    }
    
    /**
     * Returns CDbCommand instance for query reports
     * 
     * @param string $field
     * @param ItemKindENUM $kind
     * @param type $page
     * @param type $size
     * @param type $userID
     * @param type $projectID
     * @return CDbCommand 
     */
    private function createDbCommandForReports($field, ItemKindENUM $kind, $page = 1, $size = 15, $userID = NULL, $projectID = NULL) {
        $itemKind = $kind->getValue();
        if ($itemKind <> ItemKindENUM::ALL) {
            $token = array(
                'where' => array(
                    'and', 
                    'ProjectID = :projectId', 
                    'Kind = :kind',
                    "$field = :userId"
                ),
                'params' => array(
                    ':projectId' => (int) $projectID,
                    ':kind' => $itemKind,
                    ':userId' => $userID
                )
            );
        } else {
            $token = array(
                'where' => array(
                    'and', 
                    'ProjectID = :projectId', 
                    'UserID = :userId'
                ),
                'params' => array(
                    ':projectId' => (int) $projectID,
                    "$field = :userId"
                )
            );
        }
        return $this->db->createCommand()
                ->select('PROJ_ID')
                ->from(self::VIEW_ITEM_FULL_INFO)
                ->where($token['where'], $token['params'])
                ->limit($size, $page);
        
    }
    
    /**
     * Checks user id and project id for existance
     * 
     * @param int $userID
     * @param int $projectID
     * @return array
     * @throws ServiceException 
     */
    private function tryCheckUserAndProject($userID, $projectID) {
        $result = null;
        
        if ($userID == NULL) {
            $result['userID'] = $this->_defaultUserId;
        } else {
            $result['userID'] = (int) $userID;
            $uc = new UserService();
            if (!$uc->existsById($result['userID'])) {
                throw new ServiceException("Пользователь не существует.");
            }
        }
        
        if ($projectID == NULL) {
            $result['$projectID'] = $this->_defaultProjectId;
        }
        else {
            $result['$projectID'] = (int) $projectID;
            $this->tryCheckProject($result['$projectID']);    
        }
        return $result;
    }

    public function getReports(ItemKindENUM $kind, $page = 1, $size = 15, $userID = NULL, $projectID = NULL) {
        $userAndProjectArray = $this->tryCheckUserAndProject($userID, $projectID);
        $getCommand = $this->createDbCommandForReports('UserID', $kind, $page, $size, $userAndProjectArray['userID'], $userAndProjectArray['projectID']);
        $res = $getCommand->queryAll();
        return $this->normalizeItemsFromList($res);
    }

    public function getMyOrdered(ItemKindENUM $kind, ErrorFieldsENUM $field, MySQLOrderEnum $direction, $page = 1, $size = 15, $userID = NULL, $projectID = NULL) {
        $userAndProjectArray = $this->tryCheckUserAndProject($userID, $projectID);
        $getCommand = $this->createDbCommandForReports('UserID', $kind, $page, $size, $userAndProjectArray['userID'], $userAndProjectArray['projectID']);
        $res = $getCommand
                ->order($this->order($field, $direction))
                ->queryAll();
        return $this->normalizeItemsFromList($res);
    }

    public function getAssignedToMe(ItemKindENUM $kind, ErrorFieldsENUM $field, MySQLOrderEnum $direction, $page = 1, $size = 15, $userID = NULL, $projectID = NULL) {
        $userAndProjectArray = $this->tryCheckUserAndProject($userID, $projectID);
        $getCommand = $this->createDbCommandForReports('AssignedTo', $kind, $page, $size, $userAndProjectArray['userID'], $userAndProjectArray['projectID']);
        $res = $getCommand
                ->order($this->order($field, $direction))
                ->queryAll();
        return $this->normalizeItemsFromList($res);
    }

    public function getProjectOrdered($projectID, ItemKindENUM $kind, ErrorFieldsENUM $field, MySQLOrderEnum $direction, $page, $size) {
        $this->tryCheckProject($projectID);
        $itemKind = $kind->getValue();
        if ($itemKind <> ItemKindENUM::ALL) {
            $token = array(
                'where' => array(
                    'and', 
                    'ProjectID = :projectId', 
                    'Kind = :kind',
                ),
                'params' => array(
                    ':projectId' => (int) $projectID,
                    ':kind' => $itemKind,
                )
            );
        } else {
            $token = array(
                'where' => 'ProjectID = :projectId', 
                'params' => array(
                    ':projectId' => (int) $projectID,
                )
            );
        }
        $res = $this->db->createCommand()
                ->select('PROJ_ID')
                ->from(self::VIEW_ITEM_FULL_INFO)
                ->where($token['where'], $token['params'])
                ->limit($size, $page)
                ->order($this->order($field, $direction))
                ->queryAll();
        return $this->normalizeItemsFromList($res);
    }

    public function getAllReports() {
        return $this->db->createCommand()
                ->select()
                ->from(self::TABLE_ITEM)
                ->queryAll();
    }

    public function getReport($reportID) {
        return $report = $this->getReportByID($reportID);
    }

    private function getReportByID($itemId) {
        $itemId = (int) $itemId;
        $res = $this->db->createCommand()
                ->select('PROJ_ID')
                ->from(self::VIEW_ITEM_FULL_INFO)
                ->where('ID = :itemId', array('itemId' => $itemId))
                ->limit($size, $page)
                ->order($this->order($field, $direction))
                ->queryRow();
        if ($res == null) {
            return null;
        }
        $this->normalizeBugReport($res);
        return $res;
    }

    public function getPreviousItemID($itemId, $projectID = null) {
        $projectID = $projectID == null ? $this->_defaultProjectId : (int) $projectID;
        $itemId = (int) $itemId;
        return (int) $this->db->createCommand()
                ->select('ITEM_ID')
                ->from(self::VIEW_ITEM_FULL_INFO)
                ->where(
                    array(
                        'and',
                        'ITEM_ID < :itemId', 
                        'PROJ_ID = :projectId'
                    ),
                    array('itemId' => $itemId)
                )
                ->limit(1, 0)
                ->order($this->order(new ItemTableFieldsENUM(), new MySQLOrderENUM(MySQLOrderENUM::DESC)))
                ->queryScalar();
    }

    public function getNextItemID($itemID, $projectID = null) {
        $projectID = $projectID == null ? $this->_defaultProjectId : (int) $projectID;
        $itemID = (int) $itemID;
        return (int) $this->db->createCommand()
                ->select('ITEM_ID')
                ->from(self::VIEW_ITEM_FULL_INFO)
                ->where(
                    array(
                        'and',
                        'ITEM_ID > :itemId', 
                        'PROJ_ID = :projectId'
                    ),
                    array('itemId' => $itemId)
                )
                ->limit(1, 0)
                ->order($this->order(new ItemTableFieldsENUM(), new MySQLOrderENUM(MySQLOrderENUM::ASC)))
                ->queryScalar();
    }

    private function chekProjectOwnerOrReportOwner($reportID) {
        $id = (int) $id;
        $pC = new ProjectService();
        return ($this->_defaultUserId == $this->getReportOwner($reportID) || $this->_defaultUserId == $pC->isOwner($this->_defaultUserId, $this->_defaultProjectId));
    }

    public function canEditStatus($reportID, $projectID) {
        $reportID = (int) $reportID;
        $projectID = (int) $projectID;
        $user = $this->_defaultUserId;
        $isOwnerORAssigned = $this->_sql->countQuery(self::TABLE_ITEM, "ITEM_ID=$reportID AND (USER_ID=$user OR ASSGN_TO=$user)");
        $pC = new ProjectService();
        return ($isOwnerORAssigned != 0) || $this->_defaultUserId == $pC->isOwner($user, $projectID);
    }

    public function canEditData($reportID, $projectID) {
        /* $projectID=(int)$projectID;
          $pC=new ProjectsModel();
          return $this->_itemOwnerID==$this->getReportOwner($reportID) || $this->_itemOwnerID==$pC->isOwner($this->_itemOwnerID,$projectID); */
        return $this->canEditStatus($reportID, $projectID);
    }
    
    private function normalizeItemsFromList(array $items) {
        if ($items != null) {
            foreach ($items as $index => $item) {
                $this->normalizeBugReport(&$item);
                $items[$index] = $item;
            }
        }
        return $items;
    }
            

    /**
     *
     * Нормализует информацию отчёта об ошибке
     */
    private function normalizeBugReport(&$reportData) {
        switch ($reportData["PriorityLevel"]) {
            case ErrorPriorityENUM::MINIMAL:
                $reportData["PriorityLevelN"] = "Низкий";
                break;
            case ErrorPriorityENUM::NORMAL:
                $reportData["PriorityLevelN"] = "Обычный";
                break;
            case ErrorPriorityENUM::HIGH:
                $reportData["PriorityLevelN"] = "Важный";
                break;
        }
        switch ($reportData["Kind"]) {
            case ItemKindENUM::DEFECT:
                $reportData["KindN"] = "Дефект";
                break;
            case ItemKindENUM::TASK:
                $reportData["KindN"] = "Задача";
                break;
        }
        switch ($reportData["ErrorType"]) {
            case ErrorTypeENUM::BLOCK:
                $reportData["ErrorTypeN"] = "Блокирующая";
                break;
            case ErrorTypeENUM::COSMETIC:
                $reportData["ErrorTypeN"] = "Косметическая";
                break;
            case ErrorTypeENUM::CRASH:
                $reportData["ErrorTypeN"] = "Крах";
                break;
            case ErrorTypeENUM::ERROR_HANDLE:
                $reportData["ErrorTypeN"] = "Исключение";
                break;
            case ErrorTypeENUM::FUNCTIONAL:
                $reportData["ErrorTypeN"] = "Функциональня";
                break;
            case ErrorTypeENUM::MAJOR:
                $reportData["ErrorTypeN"] = "Значительная";
                break;
            case ErrorTypeENUM::MINOR:
                $reportData["ErrorTypeN"] = "Неначительная";
                break;
            case ErrorTypeENUM::SETUP:
                $reportData["ErrorTypeN"] = "Ошибка инсталляции";
                break;
        }
        switch ($reportData["Status"]) {
            case ErrorStatusENUM::IS_NEW:
                $reportData["StatusN"] = "Новый";
                break;
            case ErrorStatusENUM::IDENTIFIED:
                $reportData["StatusN"] = "Идентифицирован";
                break;
            case ErrorStatusENUM::ASSESSED:
                $reportData["StatusN"] = "В процессе";
                break;
            case ErrorStatusENUM::RESOLVED:
                $reportData["StatusN"] = "Решён";
                break;
            case ErrorStatusENUM::CLOSED:
                $reportData["StatusN"] = "Закрыт";
                break;
        }
    }

}

?>