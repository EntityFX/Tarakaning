<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author EntityFX
 */
interface IItemService {

    function setDefaultUserId($userId);

    function setDefaultProjectId($projectId);

    /**
     * @var ItemDBKindENUM тип элемента
     * @var ItemPriorityENUM приоритет элемента
     * @var ItemStatusENUM статус элемента
     */
    function addReport(ItemDBKindENUM $kind, ItemPriorityENUM $priority, ItemTypeENUM $type, $title, $hoursRequired, $description = "", $steps = "", $assignedTo = null);

    function deleteItem($reportID);

    function deleteItemsFromList($keysList, $userID = null, $projectID = null);

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
    function editItem($itemID, $userID, $projectID, $title, $hoursRequired, $addHours, ItemStatusENUM $newStatus, ItemPriorityENUM $priority, ItemTypeENUM $type, $description = "", $steps = "", $assignedTo = null);

    function existsById($itemId);

    /**
     * Returns all items from project
     *
     * @param int $projectID Project Id
     * @param ItemKindENUM $kind
     * @param int $from
     * @param int $size
     * @return array
     */
    function getReportsByProject($projectID, ItemKindENUM $kind, $from = 0, $size = 15);

    function countReportsByProject($projectID, ItemKindENUM $kind);

    /**
     *
     *
     * @param ItemKindENUM $kind
     * @return type
     */
    function countReports(ItemKindENUM $kind);

    function countAssignedReports(ItemKindENUM $kind);

    function getReports(ItemKindENUM $kind, $page = 0, $size = 15, $userID = NULL, $projectID = NULL);

    function getMyOrdered(ItemKindENUM $kind, ItemFieldsENUM $field, DBOrderENUM $direction, $page = 0, $size = 15, $userID = NULL, $projectID = NULL);

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
    function getAssignedToMe(ItemKindENUM $kind, ItemFieldsENUM $field, DBOrderENUM $direction, $page = 0, $size = 15, $userID = NULL, $projectID = NULL);

    function getProjectOrdered($projectID, ItemKindENUM $kind, ItemFieldsENUM $field, DBOrderENUM $direction, $page = 0, $size = 15);

    function getAll();

    function getReport($reportID);

    /**
     * Returns next item going after given item in the current project or returns null
     *
     * @param int $itemId Item is going left near neighbour
     * @param int $projectID Project to return from
     * @return int Next item id after given (nullable)
     */
    function getPreviousItemID($itemId, $projectID = null);

    /**
     * Returns next item going after given item in the current project or returns null
     *
     * @param int $itemId Item is going left near neighbour
     * @param int $projectID Project to return from
     * @return int Next item id after given (nullable)
     */
    function getNextItemID($itemId, $projectID = null);

    function canEditStatus($itemId, $projectID);

    function canEditData($itemId, $projectID);
}

?>
