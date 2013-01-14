<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Administrator
 */
interface ICommentService {

    /**
     * Функция для комментирования отчета об ошибке.
     * @param unknown_type $projectID
     * @param unknown_type $userID
     * @param unknown_type $reportID
     * @param unknown_type $comment
     *
     */
    public function setReportComment($projectID, $userID, $reportID, $comment);


    /**
     * Удаление комментария к отчету об ошибке.
     * @param unknown_type $projectID
     * @param unknown_type $userID
     * @param unknown_type $commentId
     * @throws ServiceException
     */
    public function deleteComment($projectID, $userID, $commentId);

    /**
     *
     * Удаляет проекты заданного пользователя
     * @param int $userID Идентификатор пользователя
     * @param array $projectsList Список проектов на удаление (ключ - идентификатор)
     */
    public function deleteCommentsFromList($userID, $commentsList);

    /**
     * Получение списка комментариев к проекту.
     * @param unknown_type $projectID
     * @param unknown_type $userID
     * @throws ServiceException
     */
    public function getProjectComments($projectID, $userID);

    /**
     * Получение списка комментариев к отчету об ошибке.
     * @param unknown_type $projectID
     * @param unknown_type $itemId
     * @param unknown_type $userID
     */
    public function getReportComments($projectID, $itemId, $userID, ItemCommentsENUM $fieldEnum, MySQLOrderENUM $direction, $page = 1, $size = 15);

    /**
     *
     * Возвращает число комментариев к данному айтему
     * @param int $itemId Идентификатор отчёта
     */
    public function getReportCommentsCount($itemId);

    /**
     * Проверка существования комментария.
     * @param unknown_type $commentId
     */
    public function isCommentExist($commentId);
    /**
     * Получение id пользователя по id комментария.
     * @param unknown_type $commentId
     */
    public function getUserIDbyCommentID($commentId);

    /**
     * Проверяет является ли данный пользователь автором данного комментария.
     * @param unknown_type $commentID
     * @param unknown_type $userID
     */
    public function isCommentOwner($commentID, $userID);

}

?>
