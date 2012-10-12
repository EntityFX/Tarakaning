<?php

class ItemService extends DBConnector {

    const VIEW_ITEM_FULL_INFO = 'view_ItemFullInfo';
    const TABLE_ITEM = 'ITEM';

    private $_itemOwnerID;
    private $_projectOwnerID;

    public function __construct(Profile $profile, $projectID = NULL, $ownerID = NULL) {
        parent::__construct();
        $projectsController = new ProjectService();
        if ($projectID == NULL) {
            $this->_itemOwnerID = $profile->id;
            if ($profile->defaultProjectID != NULL) {
                $this->_projectOwnerID = $profile->defaultProjectID;
            }
        } else {
            if ($projectsController->existsById((int) $projectID)) {
                $requestService = new RequestService();
                if ($ownerID == NULL) {
                    $this->_itemOwnerID = $profile->id;
                } else {
                    $userService = new UserService();
                    if ($userService->checkIfExsist((int) $ownerID)) {
                        $this->_itemOwnerID = (int) $ownerID;
                    } else {
                        throw new ServiceException("Пользователь не существует. Нельзя несуществующему пользователю отставлять отчёт об ошибках");
                    }
                }
                if ($requestService->isSubscribed($this->_itemOwnerID, (int) $projectID) || $this->_itemOwnerID == $projectsController->getOwnerID((int) $projectID)) {
                    $this->_projectOwnerID = (int) $projectID;
                } else {
                    throw new ServiceException("Пользователь №" . $this->_itemOwnerID . " не подписан на проект $projectID или не является его владельцем");
                }
            } else {
                throw new ServiceException("Проект не существует. Нельзя присвоить несуществующему проекту отчёты об ошибках");
            }
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
        $this->_sql->call(
                'AddItem', new ArrayObject(array(
                    $this->_itemOwnerID,
                    $this->_projectOwnerID,
                    $assignedTo,
                    $priorityValue,
                    new ErrorStatusENUM(),
                    date("Y-m-d H:i:s"),
                    $title,
                    $kindValue,
                    $hoursRequired,
                    $description,
                    $typeValue,
                    $steps
                ))
        );
        return $this->_sql->getLastID();
    }

    public function deleteReport($reportID) {
        $id = (int) $reportID;
        $this->_sql->delete(self::TABLE_ITEM, "ITEM_ID=$id");
    }

    public function deleteReportsFromList($keysList, $userID = null, $projectID = null) {
        $userID = $userID == null ? $this->_itemOwnerID : (int) $userID;
        $projectID = $projectID == null ? $this->_projectOwnerID : (int) $projectID;
        if ($keysList != '') {
            $this->_sql->call(
                    'DeleteItemsFromList', new ArrayObject(array(
                        $userID,
                        $projectID,
                        $keysList
                    ))
            );
        }
    }

    /**
     *
     * Редактировать статус задачи
     * @param $reportID int ID отчёта
     * @param $errorStatusErrorStatusENUM Новый статус
     * @param $userID int Текущий юзер
     */
    public function editItem($reportID, $userID, $projectID, $title, $hoursRequired, $addHours, ErrorStatusENUM $newStatus, ErrorPriorityENUM $priority, ErrorTypeEnum $type, $description = "", $steps = "", $assignedTo = null) {
        if ($newStatus->check()) {
            $newStatusValue = $newStatus->getValue();
        } else {
            throw new ServiceException("Неверный статус ошибки");
        }
        $report = $this->getReportByID($reportID);
        $hoursRequired = (int) $hoursRequired;
        $addHours = (int) $addHours;
        if ($report != null) {
            $currentStatusValue = $report["Status"];
            $statusesArray = $newStatus->getNumberedKeys();
            $currentValueKey = array_search($currentStatusValue, $statusesArray);
            $newValueKey = array_search($newStatusValue, $statusesArray);
            if ($this->canEditStatus($reportID, $projectID) && ($newValueKey - $currentValueKey) <= 1) {
                $editStatusFlag = false;
                $canEditData = $this->canEditData($reportID, $projectID);
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
                        $this->_sql->update(
                                self::TABLE_ITEM, "ITEM_ID=$reportID", new ArrayObject(array(
                                    "STAT" => $newStatusValue
                                ))
                        );
                    }
                    else {
                        if ($title == '')
                            throw new ServiceException("Заголовок не должен быть пустым");
                        $this->_sql->call(
                                "EditItem", new ArrayObject(array(
                                    (int) $reportID,
                                    $title,
                                    (int) $priority->getValue(),
                                    $newStatusValue,
                                    (int) $assignedTo,
                                    $hoursRequired,
                                    $addHours,
                                    $description,
                                    $type->getValue(),
                                    $steps
                                ))
                        );
                    }
                    return true;
                }
            }
        }
    }

    /**
     * Получиить владельца отчёта
     *
     * @param int $reportID ID отчёта
     * @return int
     */
    private function getReportOwner($reportID) {
        $reportID = (int) $reportID;
        $this->_sql->selFieldsWhere(self::TABLE_ITEM, "ITEM_ID=$reportID", "USER_ID");
        $res = $this->_sql->getResultRows();
        return (int) $res[0]["UserID"];
    }

    private function checkIsProjectError($reportID) {
        $reportID = (int) $reportID;
        $this->_sql->selFieldsWhere(self::TABLE_ITEM, "ITEM_ID=$reportID", "PROJ_ID");
        $projectID = $this->_sql->getResultRows();
        $projectID = $projectID[0]["ProjectID"];
        return $projectID == $this->_projectOwnerID;
    }

    public function checkIsExsist($reportId) {
        $id = (int) $reportId;
        $countGroups = $this->_sql->countQuery(self::TABLE_ITEM, "ITEM_ID=$id");
        return (Boolean) $countGroups;
    }

    public function getReportsByProject($projectID, ItemKindENUM $kind, $from, $size) {
        $this->checkProject($projectID);
        $this->useLimit($from, $size);
        $itemKind = $kind->getValue();
        if ($itemKind <> ItemKindENUM::ALL) {
            $kindExpression = "AND Kind='$itemKind'";
        }
        $this->_sql->selAllWhere(self::VIEW_ITEM_FULL_INFO, "ProjectID=$projectID $kindExpression");
        $res = $this->_sql->getResultRows();
        if ($res != null) {
            foreach ($res as $index => $report) {
                $this->normalizeBugReport(&$report);
                $res[$index] = $report;
            }
        }
        return $res;
    }

    public function countReportsByProject($projectID, ItemKindENUM $kind) {
        $this->checkProject($projectID);
        $itemKind = $kind->getValue();
        if ($itemKind <> ItemKindENUM::ALL) {
            $kindExpression = "AND Kind='$itemKind'";
        }
        return $this->_sql->countQuery(self::VIEW_ITEM_FULL_INFO, "ProjectID=$projectID $kindExpression");
    }

    private function checkProject(&$projectID) {
        if ($projectID == NULL) {
            $projectID = $this->_projectOwnerID;
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
        $userID = $this->_itemOwnerID;
        $projectID = $this->_projectOwnerID;
        $itemKind = $kind->getValue();
        if ($itemKind <> ItemKindENUM::ALL) {
            $kindExpression = "AND Kind='$itemKind'";
        }
        return $this->_sql->countQuery(self::VIEW_ITEM_FULL_INFO, "UserID=$userID AND ProjectID=$projectID $kindExpression");
    }

    public function countAssignedReports(ItemKindENUM $kind) {
        $userID = $this->_itemOwnerID;
        $projectID = $this->_projectOwnerID;
        $itemKind = $kind->getValue();
        if ($itemKind <> ItemKindENUM::ALL) {
            $kindExpression = "AND Kind='$itemKind'";
        }
        return $this->_sql->countQuery(self::VIEW_ITEM_FULL_INFO, "AssignedTo=$userID AND ProjectID=$projectID $kindExpression");
    }

    public function getReports(ItemKindENUM $kind, $page = 1, $size = 15, $userID = NULL, $projectID = NULL) {
        $res = NULL;

        if ($userID == NULL) {
            $userID = $this->_itemOwnerID;
        } else {
            $userID = (int) $userID;
            $uc = new UsersController();
            if ($uc->checkIfExsist($userID)) {
                $this->checkProject($projectID);
            } else {
                throw new ServiceException("Пользователь не существует.");
            }
        }
        if ($projectID == NULL) {
            $projectID = $this->_projectOwnerID;
        }
        $itemKind = $kind->getValue();
        if ($itemKind <> ItemKindENUM::ALL) {
            $kindExpression = "AND Kind='$itemKind'";
        }
        $this->_sql->setLimit($page, $size);
        $this->_sql->selAllWhere(self::VIEW_ITEM_FULL_INFO, "UserID=$userID AND ProjectID=$projectID $kindExpression");
        $this->_sql->clearLimit();
        $res = $this->_sql->getResultRows();
        if ($res != null) {
            foreach ($res as $index => $report) {
                $this->normalizeBugReport(&$report);
                $res[$index] = $report;
            }
        }
        return $res;
    }

    public function getMyOrdered(ItemKindENUM $kind, ErrorFieldsENUM $field, MySQLOrderEnum $direction, $page = 1, $size = 15, $userID = NULL, $projectID = NULL) {
        $this->useOrder($field, $direction);
        return $this->getReports($kind, $page, $size, $userID = NULL, $projectID = NULL);
    }

    public function getAssignedToMe(ItemKindENUM $kind, ErrorFieldsENUM $field, MySQLOrderEnum $direction, $page = 1, $size = 15, $userID = NULL, $projectID = NULL) {
        $this->_sql->setOrder($field, $direction);
        $this->_sql->setLimit($page, $size);
        if ($userID == NULL) {
            $userID = $this->_itemOwnerID;
        } else {
            $userID = (int) $userID;
            $uc = new UsersController();
            if ($uc->checkIfExsist($userID)) {
                $this->checkProject($projectID);
            } else {
                throw new ServiceException("Пользователь не существует.");
            }
        }
        if ($projectID == NULL) {
            $projectID = $this->_projectOwnerID;
        }
        $itemKind = $kind->getValue();
        if ($itemKind <> ItemKindENUM::ALL) {
            $kindExpression = "AND Kind='$itemKind'";
        }
        $this->_sql->selAllWhere(self::VIEW_ITEM_FULL_INFO, "AssignedTo=$userID AND ProjectID=$projectID $kindExpression");
        $this->_sql->clearOrder();
        $this->_sql->clearLimit();
        $res = $this->_sql->getResultRows();
        if ($res != null) {
            foreach ($res as $index => $report) {
                $this->normalizeBugReport(&$report);
                $res[$index] = $report;
            }
        }
        return $res;
    }

    public function getProjectOrdered($projectID, ItemKindENUM $kind, ErrorFieldsENUM $field, MySQLOrderEnum $direction, $from, $size) {
        $this->checkProject($projectID);
        $this->useOrder($field, $direction);
        $this->useLimit($from, $size);
        $itemKind = $kind->getValue();
        if ($itemKind <> ItemKindENUM::ALL) {
            $kindExpression = "AND Kind='$itemKind'";
        }
        $this->_sql->selAllWhere(self::VIEW_ITEM_FULL_INFO, "ProjectID=$projectID $kindExpression");
        $this->_sql->clearLimit();
        $this->_sql->clearOrder();
        $res = $this->_sql->getResultRows();
        if ($res != null) {
            foreach ($res as $index => $report) {
                $this->normalizeBugReport(&$report);
                $res[$index] = $report;
            }
        }
        return $res;
    }

    public function getAllReports() {
        $this->_sql->selAll(self::TABLE_ITEM);
        return $this->_sql->getResultRows();
    }

    public function getReport($reportID) {
        return
                $report = $this->getReportByID($reportID);
        if ($report != null) {
            
        }
    }

    private function getReportByID($reportID) {
        $reportID = (int) $reportID;
        $this->_sql->selAllWhere(self::VIEW_ITEM_FULL_INFO, "ID=$reportID");
        $arr = $this->_sql->getResultRows();
        if ($arr == null) {
            return null;
        }
        $this->normalizeBugReport($arr[0]);
        return $arr[0];
    }

    public function getPreviousItemID($itemID, $projectID = null) {
        $projectID = $projectID == null ? $this->_projectOwnerID : (int) $projectID;
        $itemID = (int) $itemID;
        $this->_sql->setLimit(0, 1);
        $this->_sql->setOrder(new ItemTableFieldsENUM(), new MySQLOrderENUM(MySQLOrderENUM::DESC));
        $this->_sql->selFieldsWhere(self::TABLE_ITEM, "ITEM_ID < $itemID AND PROJ_ID = $projectID", "ITEM_ID");
        $this->_sql->clearOrder();
        $this->_sql->clearLimit();
        $result = $this->_sql->getResultRows();
        return (int) $result[0]["ITEM_ID"];
    }

    public function getNextItemID($itemID, $projectID = null) {
        $projectID = $projectID == null ? $this->_projectOwnerID : (int) $projectID;
        $itemID = (int) $itemID;
        $this->_sql->setLimit(0, 1);
        $this->_sql->setOrder(new ItemTableFieldsENUM(), new MySQLOrderENUM(MySQLOrderENUM::ASC));
        $this->_sql->selFieldsWhere(self::TABLE_ITEM, "ITEM_ID > $itemID AND PROJ_ID = $projectID", "ITEM_ID");
        $this->_sql->clearOrder();
        $this->_sql->clearLimit();
        $result = $this->_sql->getResultRows();
        return (int) $result[0]["ITEM_ID"];
    }

    private function chekProjectOwnerOrReportOwner($reportID) {
        $id = (int) $id;
        $pC = new ProjectService();
        return ($this->_itemOwnerID == $this->getReportOwner($reportID) || $this->_itemOwnerID == $pC->isOwner($this->_itemOwnerID, $this->_projectOwnerID));
    }

    public function canEditStatus($reportID, $projectID) {
        $reportID = (int) $reportID;
        $projectID = (int) $projectID;
        $user = $this->_itemOwnerID;
        $isOwnerORAssigned = $this->_sql->countQuery(self::TABLE_ITEM, "ITEM_ID=$reportID AND (USER_ID=$user OR ASSGN_TO=$user)");
        $pC = new ProjectService();
        return ($isOwnerORAssigned != 0) || $this->_itemOwnerID == $pC->isOwner($user, $projectID);
    }

    public function canEditData($reportID, $projectID) {
        /* $projectID=(int)$projectID;
          $pC=new ProjectsModel();
          return $this->_itemOwnerID==$this->getReportOwner($reportID) || $this->_itemOwnerID==$pC->isOwner($this->_itemOwnerID,$projectID); */
        return $this->canEditStatus($reportID, $projectID);
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