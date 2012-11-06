<?php

/**
 *
 * @author EntityFX
 */
interface IRequestService {

    /**
     * Подтверждение запроса на подписку.
     * @param int $keysList - список запросов.
     * @param int $ownerID - id автора проекта.
     * @param int $projectID - id проекта.
     */
    function acceptRequest($keysList, $ownerID, $projectID);

    /**
     * Отклонение заявки.
     * @param int $requestID - id запроса.
     * @param int $userID - id пользователя, пославшего запрос.
     * @param int $projectID - id проекта.
     * @param int $ownerID - id автора проекта.
     */
    function declineRequest($requestID, $userID, $projectID, $ownerID);

    /**
     * Получение списка заявок для данного проекта.
     * @param unknown_type $userID
     * @param unknown_type $projectID
     * @param unknown_type $startIndex
     * @param unknown_type $maxCount
     */
    function getRequests($userID, $projectID, $page = 0, $size = 20);

    /**
     * Подача заявки на проект.
     * 
     * @param int $userID - id пользователя, подавшего заявку.
     * @param int $projectID - id проекта, на который пользователь подал заявку.
     * @return bool - результат подачи заявки.
     */
    function sendRequest($userID, $projectID);
}