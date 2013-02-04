<?php

/**
 *
 * @author EntityFX
 */
interface ISubscribeService {

    /**
     * Проверяется есть ли нерассмотренная заявка от данного пользователя на данный проект.
     * @param int $userID - id пользователя, подавшего заявку.
     * @param int $projectID - id проекта, на который пользователь подал заявку.
     * @return bool - результат проверки.
     */
    function isRequestExists($userID, $projectID);

    /**
     * Получение списка проектов, на которые подписан пользователь.
     * @param int $userID - id пользователя, подавшего заявку.
     */
    function getUserSubscribes($userID, SubscribesDetailENUM $orderField, DBOrderENUM $direction, $page = 0, $size = 15);

    function getSubscribesCount($userID);

    function getProjectSubscribes($projectID, ProjectSubscribesDetailENUM $orderField, DBOrderENUM $direction, $page = 0, $size = 15);

    /**
     * 
     * Возвращает количество заявок на проект
     * @param int $projectID
     */
    function getProjectRequestsCount($projectID);

    /**
     * Удаление данного пользователя из списка участников проекта.
     * @param int $userID - id пользователя.
     * @param int $projectID - id проекта, из которого пользователь удаляется.
     */
    function removeMember($userID, $projectID);

    /**
     * 
     * Удаляет участников проекта
     * @param array $keysList Список идентификаторов участников
     * @param int $projectID ID проекта
     */
    function deleteProjectMembers($keysList, $ownerID, $projectID);

    function getProjectUsers($projectID);

    function getProjectUsersPaged($projectID, $page = 0, $size = 30);

    /**
     * Проверяется подписан ли данный пользователь в данном проекте.
     * @param int $userID - id пользователя.
     * @param int $projectID - id проекта.
     */
    function isSubscribed($userID, $projectID);
}

?>
