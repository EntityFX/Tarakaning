<?php

/**
 * Класс управления комментариями к ошибкам.
 * @author timur 29.01.2011
 *
 */
class CommentService extends ServiceBase implements ICommentService {

    public function __construct($projectID = NULL, $ownerID = NULL) {
        parent::__construct();
    }

    /**
     * Функция для комментирования отчета об ошибке.
     * @param int $projectID
     * @param int $userID
     * @param int $reportID
     * @param string $commentText
     *
     * @todo 1) добавить проверку на существование отчета об ошибке. <br />
     * 2) добавить проверку на существование данного пользователя.
     */
    public function setReportComment($projectID, $userID, $reportID, $commentText) {
        /*
         * сперва проверить существование проекта
         * потом - пользователя
         * потом является ли подписанным или хозяином
         * еще проверить существует ли отчет
         */
        $projectID = (int) $projectID;
        $projectService = $this->ioc->create('IProjectService');
        if ($projectService->existsById($projectID)) {
            $userID = (int) $userID;
            if ($this->isOwnerOrSubscribed($userID, $projectID)) {
                $commentText = htmlspecialchars($commentText);
                $reportID = (int) $reportID;

                $this->db->createCommand()->insert(
                        ItemCommentTable::NAME, array(
                            ItemCommentTable::ITEM_CMMENT_ID_FIELD => 0,
                            ItemCommentTable::ITEM_ID_FIELD => $reportID,
                            ItemCommentTable::USER_ID_FIELD => $userID,
                            ItemCommentTable::CRT_TM_FIELD => date("Y-m-d H:i:s"),
                            ItemCommentTable::CMMENT_FIELD => $commentText
                        )
                );
                return $this->db->getLastInsertID();
            } else {
                throw new ServiceException("Вы не являетесь участником проекта.", 602);
            }
        } else {
            throw new ServiceException("Проект не существует.", 101);
        }
    }
    
    private function isOwnerOrSubscribed($userID, $projectID) {
        $projectService = $this->ioc->create('IProjectService');
        $subscribeService = $this->ioc->create('ISubscribeService');
        return $subscribeService->isSubscribed($userID, $projectID) || $projectService->isOwner($userID, $projectID);
    }

    /**
     * Удаление комментария к отчету об ошибке.
     * @param unknown_type $projectID
     * @param unknown_type $userID
     * @param unknown_type $commentId
     * @throws ServiceException
     */
    public function deleteComment($projectID, $userID, $commentId) {
        $userID = (int) $userID;
        $projectID = (int) $projectID;
        $projectService = $this->ioc->create('IProjectService');
        if ($projectService->existsById($projectID)) {
            if ($this->isCommentExist($commentId)) {
                if ($this->isOwnerOrSubscribed($userID, $projectID)) {
                    if ($this->isCommentOwner($commentId, $userID, $projectID)) {
                        $this->db->createCommand()
                                ->delete(
                                        ItemCommentTable::NAME,
                                        ItemCommentTable::ITEM_CMMENT_ID_FIELD . ' = :commentId',
                                        array(
                                            ':commentId' => $commentId
                                        )
                                );
                    } else {
                        throw new ServiceException("Вы не являетесь автором комментария или проекта", 1002);
                    }
                } else {
                    throw new ServiceException("Вы не являетесь участником проекта.", 602);
                }
            } else {
                throw new ServiceException("Комментария не существует.", 1001);
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
    public function deleteCommentsFromList($userID, $commentsList) {
        $userID = (int) $userID;
        if ($commentsList != null) {
            $commentsListSerialized = SerializeHelper::SerializeForStoredProcedure($commentsList);
            $query = 'CALL DeleteCommentsFromList(
                        :UserId,
                        :CommentsList
                    )';
            $deleteCommand = $this->db->createCommand($query);
            $deleteCommand->bindParam(':UserId', $userID);
            $deleteCommand->bindParam(':CommentsList', $commentsListSerialized);
            $deleteCommand->execute();
        }
    }

    /**
     * Получение списка комментариев к проекту.
     * @param unknown_type $projectID
     * @param unknown_type $userID
     * @throws ServiceException
     */
    public function getProjectComments($projectID, $userID) {
        $userID = (int) $userID;
        $projectID = (int) $projectID;
        $projectService = $this->ioc->create('IProjectService');
        if ($projectService->existsById($projectID)) {
            if ($this->isOwnerOrSubscribed($userID, $projectID)) {
                return $this->db->createCommand()
                        ->select('C.*')
                        ->from(ItemCommentTable::NAME . ' C')
                        ->join(
                                ItemTable::NAME.' I', 
                                'C.' . ItemCommentTable::ITEM_ID_FIELD . ' = I.' . ItemTable::ITEM_ID_FIELD
                        )
                        ->where(
                                'I.' . ItemTable::PROJ_ID_FIELD . ' = :projectId',
                                array(
                                    ':projectId' => $projectID
                                )
                        )
                        ->queryAll();
            } else {
                throw new ServiceException("Вы не являетесь участником проекта.", 602);
            }
        } else {
            throw new ServiceException("Проект не существует.", 101);
        }
    }

    /**
     * Получение списка комментариев к отчету об ошибке.
     * @param unknown_type $projectID
     * @param unknown_type $itemId
     * @param unknown_type $userID
     */
    public function getReportComments($projectID, $itemId, $userID, ItemCommentsENUM $fieldEnum, MySQLOrderENUM $direction, $page = 1, $size = 15) {
        $userID = (int) $userID;
        $projectID = (int) $projectID;
        $itemId = (int) $itemId;
        $startIndex = (int) $startIndex;
        $maxCount = (int) $maxCount;
        $projectService = $this->ioc->create('IProjectService');
        if ($projectService->existsById($projectID)) {
            if ($this->isOwnerOrSubscribed($userID, $projectID)) {
                return $this->db->createCommand()
                    ->select()
                    ->from(CommentsDetailView::NAME)
                    ->where(CommentsDetailView::ITEM_ID_FIELD . ' = :itemId',array(':itemId' => $itemId))
                    ->order($this->order($fieldEnum, $direction))
                    ->limit($size,$page)
                    ->queryAll();
            } else {
                throw new ServiceException("Вы не являетесь участником проекта.", 602);
            }
        } else {
            throw new ServiceException("Проект не существует.", 101);
        }
    }

    /**
     *
     * Возвращает число комментариев к данному айтему
     * @param int $itemId Идентификатор отчёта
     */
    public function getReportCommentsCount($itemId) {
        $itemId = (int) $itemId;
        return $this->getCount(
                CommentsDetailView::NAME,
                CommentsDetailView::ITEM_ID_FIELD . ' = :itemId',
                array(
                    ':itemId' => $itemId
                )
        );
    }

    /**
     * Проверка существования комментария.
     * @param unknown_type $commentId
     */
    public function isCommentExist($commentId) {
        return $this->db->createCommand()
                ->select(ItemCommentTable::ITEM_CMMENT_ID_FIELD)
                ->from(ItemCommentTable::NAME)
                ->where(ItemCommentTable::ITEM_CMMENT_ID_FIELD . ' = :comentId',array(':comentId' => $commentId))
                ->queryScalar() !== false;
    }

    /**
     * Получение id пользователя по id комментария.
     * @param unknown_type $commentId
     */
    public function getUserIDbyCommentID($commentId) {
        $commentId = (int) $commentId;
        return (int) $this->db->createCommand()
                ->select(ItemCommentTable::USER_ID_FIELD)
                ->from(ItemCommentTable::NAME)
                ->where(ItemCommentTable::ITEM_CMMENT_ID_FIELD . ' = :comentId',array(':comentId' => $commentId))
                ->queryScalar();
    }

    /**
     * Проверяет является ли данный пользователь автором данного комментария.
     * @param unknown_type $commentID
     * @param unknown_type $userID
     */
    public function isCommentOwner($commentID, $userID) {
        $userID = (int) $userID;
        $commentID = (int) $commentID;
        return $this->getUserIDbyCommentID($commentID) == $userID;
    }

}

?>