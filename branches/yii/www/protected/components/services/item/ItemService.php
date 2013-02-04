<?php

class ItemService extends ServiceBase implements IItemService {

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

        if ($this->_defaultProjectId !== null
                && $this->_defaultUserId != null) {
            $this->tryCheckIsSubscribedOrOwner($this->_defaultUserId, $this->_defaultProjectId);
        }
    }

    public function setDefaultUserId($userId) {
        $userId = (int) $userId;
        $userService = $this->ioc->create('IUserService');
        if ($userService->existsById($userId)) {
            $this->_defaultUserId = (int) $userId;
        } else {
            throw new ServiceException("Пользователь не существует. Нельзя несуществующему пользователю отставлять отчёт об ошибках");
        }
    }

    public function setDefaultProjectId($projectId) {
        $projectId = (int) $projectId;
        $projectService = $this->ioc->create('IProjectService');
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
        $query = 'CALL ' .AddItemProcedure::NAME. '(
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
                    ItemTable::NAME, ItemTable::ITEM_ID_FIELD . ' =:itemId', array(
                        ':itemId' => $id
                    )
        );
    }

    public function deleteItemsFromList($keysList, $userID = null, $projectID = null) {
        $userID = $userID == null ? $this->_defaultUserId : (int) $userID;
        $projectID = $projectID == null ? $this->_defaultProjectId : (int) $projectID;
        $keysList = SerializeHelper::SerializeForStoredProcedure($keysList);
        if ($keysList != '') {
            $query = 'CALL ' .DeleteItemsFromListProcedure::NAME .'(
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
            $currentStatusValue = $report[ItemFullInfoView::STATUS_FIELD];
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
                } else if ($userID == $report[ItemFullInfoView::USER_ID_FIELD]) {
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
                        $query = 'CALL ' .EditItemProcedure::NAME . '(
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
                        ->select(ItemTable::USER_ID_FIELD)
                        ->from(ItemTable::NAME)
                        ->where(ItemTable::ITEM_ID_FIELD . '= :itemId', array(':itemId' => $itemId))
                        ->queryScalar();
    }

    private function isDefaultProjectItem($itemId) {
        $itemId = (int) $itemId;
        $projectID = $this->db->createCommand()
                ->select(array(ItemTable::PROJ_ID_FIELD))
                ->from(ItemTable::NAME)
                ->where(ItemTable::ITEM_ID_FIELD . '= :itemId', array(':itemId' => $itemId))
                ->queryScalar();
        return $projectID == $this->_defaultProjectId;
    }

    public function existsById($itemId) {
        $id = (int) $itemId;
        return $this->db->createCommand()
                        ->select(ItemTable::PROJ_ID_FIELD)
                        ->from(ItemTable::NAME)
                        ->where(ItemTable::ITEM_ID_FIELD . '= :itemId', array(':itemId' => $id))
                        ->queryScalar() !== false;
    }

    private function getWhereTokenForItemKind(ItemKindENUM $kind, $projectId) {
        $token = null;
        $itemKind = $kind->getValue();
        if ($itemKind <> ItemKindENUM::ALL) {
            $token = array(
                'where' => array('and', ItemFullInfoView::PROJECT_ID_FIELD . '= :projectId', ItemFullInfoView::KIND_FIELD . '= :kind'),
                'params' => array(
                    ':projectId' => (int) $projectId,
                    ':kind' => $itemKind
                )
            );
        } else {
            $token = array(
                'where' => ItemFullInfoView::PROJECT_ID_FIELD . '= :projectId',
                'params' => array(':projectId' => (int) $projectId)
            );
        }
        return $token;
    }
    
    private function prepareWhereArrayForItemKind(ItemKindENUM $kind, array &$token)
    {
        $itemKind = $kind->getValue();
        if ($itemKind <> ItemKindENUM::ALL) {
            if (is_string($token['where'])) {
                $token['where'] = array(
                    'and',
                    $token['where'],
                    ItemFullInfoView::KIND_FIELD . '= :kind'
                );
            }
            else {
                $token['where'][] = ItemFullInfoView::KIND_FIELD . '= :kind';
            }
            $token['params'][':kind'] = $itemKind;
        }
    }

    /**
     * Returns all items from project
     *
     * @param int $projectID Project Id
     * @param ItemKindENUM $kind
     * @param int $from
     * @param int $size
     * @return array
     */
    public function getReportsByProject($projectID, ItemKindENUM $kind, $from = 0, $size = 15) {
        $this->tryCheckProject($projectID);
        $token = $this->getWhereTokenForItemKind($kind, $projectID);
        $res = $this->db->createCommand()
                ->select()
                ->from(ItemFullInfoView::NAME)
                ->where($token['where'], $token['params'])
                ->limit($size, $from)
                ->queryAll();
        return $this->normalizeItemsFromList($res);
    }

    public function countReportsByProject($projectID, ItemKindENUM $kind) {
        $this->tryCheckProject($projectID);
        $whereArray = $this->getWhereTokenForItemKind($kind, $projectID);
        return $this->getCount(
                        ItemFullInfoView::NAME, $whereArray['where'], $whereArray['params']
        );
    }

    private function tryCheckProject(&$projectID) {
        if ($projectID == NULL) {
            $projectID = $this->_defaultProjectId;
        } else {
            $pc = $this->ioc->create('IProjectService');
            if ($pc->existsById((int) $projectID)) {
                $projectID = (int) $projectID;
            } else {
                throw new ServiceException("Проект не существует. Нельзя получить список ошибок по несуществующему проекту");
            }
        }
    }

    /**
     *
     *
     * @param ItemKindENUM $kind
     * @return type
     */
    public function countReports(ItemKindENUM $kind) {
        $userId = $this->_defaultUserId;
        $projectId = $this->_defaultProjectId;
        $whereArray = $this->createWhereArrayForReadItems(ItemFullInfoView::USER_ID_FIELD, $projectId, $userId);
        $this->prepareWhereArrayForItemKind($kind, $whereArray);
        return $this->getCount(
                        ItemFullInfoView::NAME, $whereArray['where'], $whereArray['params']
        );
    }

    public function countAssignedReports(ItemKindENUM $kind) {
        $userId = $this->_defaultUserId;
        $projectId = $this->_defaultProjectId;
        $whereArray = $this->createWhereArrayForReadItems(ItemFullInfoView::ASSIGNED_TO_FIELD, $projectId, $userId);
        $this->prepareWhereArrayForItemKind($kind, $whereArray);
        return $this->getCount(
                        ItemFullInfoView::NAME, $whereArray['where'], $whereArray['params']
        );
    }

    /**
     * Creates array with 'where' and 'param' keys for query
     *
     * @param string $field Database field name
     * @return array
     */
    private function createWhereArrayForReadItems($field, $projectId, $userId) {
        return array(
            'where' => array(
                'and',
                ItemFullInfoView::PROJECT_ID_FIELD . '= :projectId',
                "$field = :userId"
            ),
            'params' => array(
                ':projectId' => (int) $projectId,
                ':userId' => $userId
            )
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
     * @param type $projectId
     * @return CDbCommand 
     */
    private function createDbCommandForReadItems($field, ItemKindENUM $kind, $page, $size, $userId = NULL, $projectId = NULL) {
        $whereArray = $this->createWhereArrayForReadItems($field, $projectId, $userId);
        $this->prepareWhereArrayForItemKind($kind, $whereArray);
        return $this->db->createCommand()
                ->select()
                ->from(ItemFullInfoView::NAME)
                ->where($whereArray['where'], $whereArray['params'])
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
            $uc = $this->ioc->create('IUserService');
            if (!$uc->existsById($result['userID'])) {
                throw new ServiceException("Пользователь не существует.");
            }
        }
        
        if ($projectID == NULL) {
            $result['projectID'] = $this->_defaultProjectId;
        }
        else {
            $result['projectID'] = (int) $projectID;
            $this->tryCheckProject($result['projectID']);    
        }
        return $result;
    }

    public function getReports(ItemKindENUM $kind, $page = 0, $size = 15, $userID = NULL, $projectID = NULL) {
        $userAndProjectArray = $this->tryCheckUserAndProject($userID, $projectID);
        $getCommand = $this->createDbCommandForReadItems(
                ItemFullInfoView::USER_ID_FIELD, 
                $kind, 
                $page, 
                $size, 
                $userAndProjectArray[ItemFullInfoView::USER_ID_FIELD], 
                $userAndProjectArray[ItemFullInfoView::PROJECT_ID_FIELD]
        );
        $res = $getCommand->queryAll();
        return $this->normalizeItemsFromList($res);
    }

    public function getMyOrdered(ItemKindENUM $kind, ItemFieldsENUM $field, DBOrderENUM $direction, $page = 0, $size = 15, $userID = NULL, $projectID = NULL) {
        $userAndProjectArray = $this->tryCheckUserAndProject($userID, $projectID);
        $getCommand = $this->createDbCommandForReadItems(
                ItemFullInfoView::USER_ID_FIELD, 
                $kind, 
                $page, 
                $size, 
                $userAndProjectArray[ItemFullInfoView::USER_ID_FIELD],
                $userAndProjectArray[ItemFullInfoView::PROJECT_ID_FIELD]
        );
        $res = $getCommand
                ->order($this->order($field, $direction))
                ->queryAll();
        return $this->normalizeItemsFromList($res);
    }

    /**
     *
     * @param ItemKindENUM $kind
     * @param ItemFieldsENUM $field
     * @param DBOrderENUM $direction
     * @param type $page
     * @param type $size
     * @param type $userID
     * @param type $projectID
     * @return type 
     */
    public function getAssignedToMe(ItemKindENUM $kind, ItemFieldsENUM $field, DBOrderENUM $direction, $page = 0, $size = 15, $userID = NULL, $projectID = NULL) {
        $userAndProjectArray = $this->tryCheckUserAndProject($userID, $projectID);
        $getCommand = $this->createDbCommandForReadItems(ItemFullInfoView::ASSIGNED_TO_FIELD, $kind, $page, $size, $userAndProjectArray['userID'], $userAndProjectArray['projectID']);
        $res = $getCommand
                ->order($this->order($field, $direction))
                ->queryAll();
        return $this->normalizeItemsFromList($res);
    }

    public function getProjectOrdered($projectID, ItemKindENUM $kind, ItemFieldsENUM $field, DBOrderENUM $direction, $page = 0, $size = 15) {
        $this->tryCheckProject($projectID);
        $whereArray = array(
            'where' => 'ProjectID = :projectId', 
            'params' => array(
                ':projectId' => (int) $projectID,
            )
        );
        $this->prepareWhereArrayForItemKind($kind, $whereArray);
        $res = $this->db->createCommand()
                ->select()
                ->from(ItemFullInfoView::NAME)
                ->where($whereArray['where'], $whereArray['params'])
                ->limit($size, $page)
                ->order($this->order($field, $direction))
                ->queryAll();
        return $this->normalizeItemsFromList($res);
    }

    public function getAll() {
        return $this->db->createCommand()
                ->select()
                ->from(ItemTable::NAME)
                ->queryAll();
    }

    public function getReport($reportID) {
        return $this->getReportByID($reportID);
    }

    private function getReportByID($itemId) {
        $itemId = (int) $itemId;
        $res = $this->db->createCommand()
                ->select()
                ->from(ItemFullInfoView::NAME)
                ->where(ItemFullInfoView::ITEM_ID_FIELD.'= :itemId', array('itemId' => $itemId))
                ->queryRow();
        if ($res == null) {
            return null;
        }
        return $res;
    }

    /**
     * Returns previous or next value by sign
     *
     * @param string $sign '<' or '>'
     * @param int $itemId Near neighbor item
     * @param int $projectID Project id
     * @return int value or NULL
     */
    private function getPreviousOrNextItemIdBySign($sign, $itemId, $projectID) {
        if ($sign === '<' || $sign === '>') {
            $projectID = $projectID == null ? $this->_defaultProjectId : (int) $projectID;
            $itemId = (int) $itemId;
            $res = $this->db->createCommand()
                    ->select('ITEM_ID')
                    ->from(ItemTable::NAME)
                    ->where(
                        array(
                            'and',
                            "ITEM_ID {$sign} :itemId",
                            'PROJ_ID = :projectId'
                        ),
                        array(
                            ':itemId' => $itemId,
                            ':projectId' => $projectID
                        )
                    )
                    ->limit(1, 0)
                    ->order($this->order(
                            new ItemTableFieldsENUM(),
                            $sign === '>' ?
                                new DBOrderENUM(DBOrderENUM::ASC) :
                                new DBOrderENUM(DBOrderENUM::DESC)
                        )
                    )
                    ->queryScalar();
            return !$res ? null : (int) $res;
        } else {
            return null;
        }
    }

    /**
     * Returns next item going after given item in the current project or returns null
     *
     * @param int $itemId Item is going left near neighbour
     * @param int $projectID Project to return from
     * @return int Next item id after given (nullable)
     */
    public function getPreviousItemID($itemId, $projectID = null) {
        return $this->getPreviousOrNextItemIdBySign('<', $itemId, $projectID);
    }

    /**
     * Returns next item going after given item in the current project or returns null
     *
     * @param int $itemId Item is going left near neighbour
     * @param int $projectID Project to return from
     * @return int Next item id after given (nullable)
     */
    public function getNextItemID($itemId, $projectID = null) {
        return $this->getPreviousOrNextItemIdBySign('>', $itemId, $projectID);
    }

    private function chekProjectOwnerOrReportOwner($reportID) {
        $id = (int) $id;
        $pC = $this->ioc->create('IProjectService');
        return ($this->_defaultUserId == $this->getReportOwner($reportID) || $this->_defaultUserId == $pC->isOwner($this->_defaultUserId, $this->_defaultProjectId));
    }

    public function canEditStatus($itemId, $projectID) {
        $itemId = (int) $itemId;
        $projectID = (int) $projectID;
        $user = $this->_defaultUserId;
        $isOwnerORAssigned = $this->getCount(
                ItemTable::NAME, 
                array(
                    'and',
                    ItemTable::ITEM_ID_FIELD . '= :itemId',
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
        $pC = $this->ioc->create('IProjectService');
        return ($isOwnerORAssigned != 0) || $this->_defaultUserId == $pC->isOwner($user, $projectID);
    }

    public function canEditData($itemId, $projectID) {
        /* $projectID=(int)$projectID;
          $pC=new ProjectsModel();
          return $this->_itemOwnerID==$this->getReportOwner($reportID) || $this->_itemOwnerID==$pC->isOwner($this->_itemOwnerID,$projectID); */
        return $this->canEditStatus($itemId, $projectID);
    }
    
}

?>