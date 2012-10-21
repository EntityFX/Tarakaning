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
     * @var ItemPriorityENUM приоритет элемента
     * @var ItemStatusENUM статус элемента
     */
    public function addReport(ItemDBKindENUM $kind, ItemPriorityENUM $priority, ItemTypeENUM $type, $title, $hoursRequired, $description = "", $steps = "", $assignedTo = null) {
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
        $query = 'CALL AddItem(
                    :UserId, 
                    :ProjectId, 
                    :AssignedTo, 
                    :PriorityLevel, 
                    :Status, 
                    :CreateDateTime, 
                    :Title, 
                    :Kind,
                    :HourRequired,
                    :Description,
                    :ItemType,
                    :StepsText
                 )';
        $addCommand = $this->db->createCommand($query);
        $addCommand->bindParam(':UserId', $this->_defaultUserId);
        $addCommand->bindParam(':ProjectId', $this->_defaultProjectId);
        $addCommand->bindParam(':AssignedTo', $assignedTo);
        $addCommand->bindParam(':PriorityLevel', $priorityValue);
        $itemStatusEnum = new ItemStatusENUM();
        $statusValue = $itemStatusEnum->getValue();
        $addCommand->bindParam(':Status', $statusValue);
        $dateValue = date("Y-m-d H:i:s");
        $addCommand->bindParam(':CreateDateTime', $dateValue);
        $addCommand->bindParam(':Title', $title);
        $addCommand->bindParam(':Kind', $kindValue);
        $addCommand->bindParam(':HourRequired', $hoursRequired);
        $addCommand->bindParam(':Description', $description);
        $addCommand->bindParam(':ItemType', $typeValue);
        $addCommand->bindParam(':StepsText', $steps);
        
        $addItemTransaction = $this->db->beginTransaction();
        $addCommand->execute();
        $insertedItemId = $this->lastInsertId();
        $addItemTransaction->commit();
        return $insertedItemId;
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

    public function deleteItemsFromList($keysList, $userID = null, $projectID = null) {
        $userID = $userID == null ? $this->_defaultUserId : (int) $userID;
        $projectID = $projectID == null ? $this->_defaultProjectId : (int) $projectID;
        $keysList = SerializeHelper::SerializeForStoredProcedure($keysList);
        if ($keysList != '') {
            $query = 'CALL DeleteItemsFromList(
                        :UserId, 
                        :ProjectId, 
                        :ItemsList 
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
     * @param type $itemID ID отчёта
     * @param int $userID
     * @param type $projectID
     * @param type $title
     * @param type $hoursRequired
     * @param type $addHours
     * @param ItemStatusENUM $newStatus Новый статус
     * @param ItemPriorityENUM $priority
     * @param ItemTypeENUM $type
     * @param type $description
     * @param type $steps
     * @param type $assignedTo
     * @return boolean
     * @throws ServiceException 
     */
    public function editItem($itemID, $userID, $projectID, $title, $hoursRequired, $addHours, ItemStatusENUM $newStatus, ItemPriorityENUM $priority, ItemTypeENUM $type, $description = "", $steps = "", $assignedTo = null) {
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
                if ($currentStatusValue != ItemStatusENUM::CLOSED) {
                    if ($currentStatusValue == ItemStatusENUM::RESOLVED) {
                        $editStatusFlag = $newStatusValue != ItemStatusENUM::CLOSED ? true : $canEditData;
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
                        $priorityValue = (int) $priority->getValue();
                        $editCommand->bindParam(':PriorityLevel', $priorityValue);
                        $editCommand->bindParam(':StatusValue', $newStatusValue);
                        $assignedValue = (int) $assignedTo;
                        $editCommand->bindParam(':AssignedTo', $assignedValue);
                        $editCommand->bindParam(':HoursRequired', $hoursRequired);
                        $editCommand->bindParam(':AddHours', $addHours);
                        $editCommand->bindParam(':Description', $description);
                        $defectTypeValue = $type->getValue();
                        $editCommand->bindParam(':DefectType', $defectTypeValue);
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
    
    public function readyP(ItemKindENUM $kind, $field, $projectId, $userId)
    {
        $token = array(
            'where' => array(
                'and', 
                'ProjectID = :projectId', 
                "$field = :userId"
            ),
            'params' => array(
                ':projectId' => (int) $projectId,
                ':userId' => $userId
            )
        );
        $this->prepareWhereTokenForItemKind($kind, $token);
        CVarDumper::dump($token, 10, true);
    }
    
    private function prepareWhereTokenForItemKind(ItemKindENUM $kind, array &$token)
    {
        $itemKind = $kind->getValue();
        if ($itemKind <> ItemKindENUM::ALL) {
            if (is_string($token['where'])) {
                $token['where'] = array(
                    'and',
                    $token['where'],
                    'Kind = :kind'
                );
            }
            else {
                $token['where'][] = 'Kind = :kind';
            }
            $token['params'][':kind'] = $itemKind;
        }
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
    private function createDbCommandForReadItems($field, ItemKindENUM $kind, $page = 1, $size = 15, $userID = NULL, $projectID = NULL) {
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
        $getCommand = $this->createDbCommandForReadItems('UserID', $kind, $page, $size, $userAndProjectArray['userID'], $userAndProjectArray['projectID']);
        $res = $getCommand->queryAll();
        return $this->normalizeItemsFromList($res);
    }

    public function getMyOrdered(ItemKindENUM $kind, ItemFieldsENUM $field, MySQLOrderEnum $direction, $page = 1, $size = 15, $userID = NULL, $projectID = NULL) {
        $userAndProjectArray = $this->tryCheckUserAndProject($userID, $projectID);
        $getCommand = $this->createDbCommandForReadItems('UserID', $kind, $page, $size, $userAndProjectArray['userID'], $userAndProjectArray['projectID']);
        $res = $getCommand
                ->order($this->order($field, $direction))
                ->queryAll();
        return $this->normalizeItemsFromList($res);
    }

    /**
     *
     * @param ItemKindENUM $kind
     * @param ItemFieldsENUM $field
     * @param MySQLOrderEnum $direction
     * @param type $page
     * @param type $size
     * @param type $userID
     * @param type $projectID
     * @return type 
     */
    public function getAssignedToMe(ItemKindENUM $kind, ItemFieldsENUM $field, MySQLOrderEnum $direction, $page = 1, $size = 15, $userID = NULL, $projectID = NULL) {
        $userAndProjectArray = $this->tryCheckUserAndProject($userID, $projectID);
        $getCommand = $this->createDbCommandForReadItems('AssignedTo', $kind, $page, $size, $userAndProjectArray['userID'], $userAndProjectArray['projectID']);
        $res = $getCommand
                ->order($this->order($field, $direction))
                ->queryAll();
        return $this->normalizeItemsFromList($res);
    }

    public function getProjectOrdered($projectID, ItemKindENUM $kind, ItemFieldsENUM $field, MySQLOrderEnum $direction, $page, $size) {
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

    public function getAll() {
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
                ->select()
                ->from(self::VIEW_ITEM_FULL_INFO)
                ->where('ID = :itemId', array('itemId' => $itemId))
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

    public function canEditStatus($itemId, $projectID) {
        $itemId = (int) $itemId;
        $projectID = (int) $projectID;
        $user = $this->_defaultUserId;
        $isOwnerORAssigned = $this->getCount(
                self::TABLE_ITEM, 
                array(
                    'and',
                    'ITEM_ID = :itemId',
                    array(
                        'or',
                        'USER_ID = :userId',
                        'ASSGN_TO = :assignedTo'
                    )
                ), 
                array(
                    ':itemId' => $itemId,
                    ':userId' => $user,
                    ':assignedTo' => $user
                )
        );
        $pC = new ProjectService();
        return ($isOwnerORAssigned != 0) || $this->_defaultUserId == $pC->isOwner($user, $projectID);
    }

    public function canEditData($itemId, $projectID) {
        /* $projectID=(int)$projectID;
          $pC=new ProjectsModel();
          return $this->_itemOwnerID==$this->getReportOwner($reportID) || $this->_itemOwnerID==$pC->isOwner($this->_itemOwnerID,$projectID); */
        return $this->canEditStatus($itemId, $projectID);
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
            case ItemPriorityENUM::MINIMAL:
                $reportData["PriorityLevelN"] = "Низкий";
                break;
            case ItemPriorityENUM::NORMAL:
                $reportData["PriorityLevelN"] = "Обычный";
                break;
            case ItemPriorityENUM::HIGH:
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
            case ItemTypeENUM::BLOCK:
                $reportData["ErrorTypeN"] = "Блокирующая";
                break;
            case ItemTypeENUM::COSMETIC:
                $reportData["ErrorTypeN"] = "Косметическая";
                break;
            case ItemTypeENUM::CRASH:
                $reportData["ErrorTypeN"] = "Крах";
                break;
            case ItemTypeENUM::ERROR_HANDLE:
                $reportData["ErrorTypeN"] = "Исключение";
                break;
            case ItemTypeENUM::FUNCTIONAL:
                $reportData["ErrorTypeN"] = "Функциональня";
                break;
            case ItemTypeENUM::MAJOR:
                $reportData["ErrorTypeN"] = "Значительная";
                break;
            case ItemTypeENUM::MINOR:
                $reportData["ErrorTypeN"] = "Неначительная";
                break;
            case ItemTypeENUM::SETUP:
                $reportData["ErrorTypeN"] = "Ошибка инсталляции";
                break;
        }
        switch ($reportData["Status"]) {
            case ItemStatusENUM::IS_NEW:
                $reportData["StatusN"] = "Новый";
                break;
            case ItemStatusENUM::IDENTIFIED:
                $reportData["StatusN"] = "Идентифицирован";
                break;
            case ItemStatusENUM::ASSESSED:
                $reportData["StatusN"] = "В процессе";
                break;
            case ItemStatusENUM::RESOLVED:
                $reportData["StatusN"] = "Решён";
                break;
            case ItemStatusENUM::CLOSED:
                $reportData["StatusN"] = "Закрыт";
                break;
        }
    }

}

?>