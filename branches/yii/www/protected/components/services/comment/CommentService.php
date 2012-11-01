<?php

/**
 * Класс управления комментариями к ошибкам.
 * @author timur 29.01.2011
 *
 */
class CommentService extends ServiceBase implements ICommentService {

    const TABLE_ITEM_COMMENT = 'ITEM_CMMENT';
    const VIEW_COMMENTS_DETAIL = 'view_CommentsDetail';

    public function __construct($projectID = NULL, $ownerID = NULL) {
        parent::__construct();
    }

    /**
     * Функция для комментирования отчета об ошибке.
     * @param unknown_type $projectID
     * @param unknown_type $userID
     * @param unknown_type $reportID
     * @param unknown_type $comment
     *
     * @todo 1) добавить проверку на существование отчета об ошибке. <br />
     * 2) добавить проверку на существование данного пользователя.
     */
    public function setReportComment($projectID, $userID, $reportID, $comment) {
        /*
         * сперва проверить существование проекта
         * потом - пользователя
         * потом является ли подписанным или хозяином
         * еще проверить существует ли отчет
         */
        $projectID = (int) $projectID;
        $projectService = new ProjectService();
        if ($projectService->existsById($projectID)) {
            $requestService = new RequestService();
            $userID = (int) $userID;
            if ($this->isOwnerOrSubscribed($userID, $projectID)) {
                $comment = htmlspecialchars($comment);
                $comment = mysql_escape_string($comment);
                $reportID = (int) $reportID;

                $this->db->createCommand()->insert(
                        self::TABLE_ITEM_COMMENT, array(
                            'ITEM_CMMENT_ID' => 0,
                            'ITEM_ID' => $reportID,
                            'USER_ID' => $userID,
                            'CRT_TM' => date("Y-m-d H:i:s"),
                            'CMMENT' => $comment
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
        $projectService = new ProjectService();
        $subscribeService = new SubscribeService();
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
        $p = new ProjectService();
        if ($p->existsById($projectID)) {
            if ($this->isCommentExist($commentId)) {
                $subscribeService = new SubscribeService();
                if ($this->isOwnerOrSubscribed($userID, $projectID)) {
                    if ($this->isCommentOwner($commentId, $userID, $projectID)) {
                        $this->db->createCommand()
                                ->delete(
                                        self::TABLE_ITEM_COMMENT,
                                        'ITEM_CMMENT_ID = :commentId',
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
        $p = new ProjectService();
        if ($p->existsById($projectID)) {
            if ($this->isOwnerOrSubscribed($userID, $projectID)) {
                return $this->db->createCommand()
                        ->select('C.*')
                        ->from(self::TABLE_ITEM_COMMENT.' C')
                        ->join(
                                ItemService::TABLE_ITEM.' I', 
                                'C.ITEM_ID = I.ITEM_ID'
                        )
                        ->where(
                                'I.PROJ_ID = :projectId',
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
        $p = new ProjectService();
        if ($p->existsById($projectID)) {
            if ($this->isOwnerOrSubscribed($userID, $projectID)) {
                return $this->db->createCommand()
                    ->select()
                    ->from(self::VIEW_COMMENTS_DETAIL)
                    ->where('ItemID = :itemId',array(':itemId' => $itemId))
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
                self::VIEW_COMMENTS_DETAIL,
                'ItemID = :itemId',
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
                ->select('ITEM_CMMENT_ID')
                ->from(self::TABLE_ITEM_COMMENT)
                ->where('ITEM_CMMENT_ID = :comentId',array(':comentId' => $commentId))
                ->queryScalar() !== false;
    }

    /**
     * Получение id пользователя по id комментария.
     * @param unknown_type $commentId
     */
    public function getUserIDbyCommentID($commentId) {
        $commentId = (int) $commentId;
        return (int) $this->db->createCommand()
                ->select('USER_ID')
                ->from(self::TABLE_ITEM_COMMENT)
                ->where('ITEM_CMMENT_ID = :comentId',array(':comentId' => $commentId))
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