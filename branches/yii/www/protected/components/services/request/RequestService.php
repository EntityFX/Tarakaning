<?php

/**
 * Класс подтверждения/отклонения на подписку.
 * 
 * @author entityfx
 *
 */
class RequestService extends ServiceBase implements IRequestService {

    public function __construct() {
        parent::__construct();
    }

    /*
     * 1) подтвердить заявку (взять из таблицы SubscribesRequest, записать в таблицу UsersInProjects)
     * 2) отклонить заявку (удалить из таблицы SubscribesRequest)
     * 3) показать список всех заявок (получить из SubscribesRequest)
     */

    /**
     * Подтверждение запроса на подписку.
     * @param int $keysList - список запросов.
     * @param int $ownerID - id автора проекта.
     * @param int $projectID - id проекта.
     */
    public function acceptRequest($keysList, $ownerID, $projectID) {
        $ownerID = (int) $ownerID;
        $projectID = (int) $projectID;

        $projectService = $this->ioc->create('IProjectService');
        if ($projectService->isOwner($ownerID, $projectID)) {
            $keysListSerialized = SerializeHelper::SerializeForStoredProcedure($keysList);
            $query = 'CALL AcceptRequest(:projectId, :keysList)';
            $deleteCommand = $this->db->createCommand($query);
            $deleteCommand->bindParam(':projectId', $projectID);
            $deleteCommand->bindParam(':keysList', $keysListSerialized);
            $deleteCommand->execute();
        }
    }

    /**
     * Отклонение заявки.
     * @param int $requestID - id запроса.
     * @param int $userID - id пользователя, пославшего запрос.
     * @param int $projectID - id проекта.
     * @param int $ownerID - id автора проекта.
     */
    public function declineRequest($requestID, $userID, $projectID, $ownerID) {
        $userID = (int) $userID;
        $projectID = (int) $projectID;
        $projectsService = $this->ioc->create('IProjectService');
        if ($projectsService->existsById($projectID)) {
            $requestID = (int) $requestID;
            $ownerID = (int) $ownerID;

            if ($projectsService->isOwner($ownerID, $projectID)) {
                $subscribeService = $this->ioc->create('ISubscribeService');
                if ($subscribeService->isSubscribed($userID, $projectID)) {
                    throw new ServiceException("Пользователь уже участвует в проекте.", 601);
                } else {
                    $this->db->createCommand()
                            ->delete(
                                SubscribeRequestTable::NAME, 
                                SubscribeRequestTable::SUBSCR_RQST_ID_FIELD . ' =:id', 
                                array(
                                    ':id' => $requestID
                                )
                            );
                    return true;
                }
            } else {
                throw new ServiceException("Не Вы являетесь Создателем проекта.", 102);
            }
        } else {
            throw new ServiceException("Проект не существует.", 101);
        }
    }

    /**
     * Получение списка заявок для данного проекта.
     * @param unknown_type $userID
     * @param unknown_type $projectID
     * @param unknown_type $startIndex
     * @param unknown_type $maxCount
     */
    public function getRequests($userID, $projectID, $page = 0, $size = 20) {
        $userID = (int) $userID;
        $projectID = (int) $projectID;
        $projectService = $this->ioc->create('IProjectService');
        if ($projectService->existsById($projectID)) {
            if ($projectService->isOwner($userID, $projectID)) {
                $page = (int) $page;
                $size = (int) $size;
                return $this->db->createCommand()
                        ->select()
                        ->from(SubscribeRequestTable::NAME)
                        ->where(
                                SubscribeRequestTable::PROJ_ID_FIELD . ' = :projectId',
                                array(
                                    ':projectId' => $projectID
                                )
                         )
                        ->limit($size, $page)
                        ->queryAll();
            } else {
                throw new ServiceException("Не Вы являетесь Создателем проекта.", 102);
            }
        } else {
            throw new ServiceException("Проект не существует.", 101);
        }
    }
    
    /**
     * Подача заявки на проект.
     * 
     * @param int $userID - id пользователя, подавшего заявку.
     * @param int $projectID - id проекта, на который пользователь подал заявку.
     * @return bool - результат подачи заявки.
     */
    public function sendRequest($userID, $projectID) {
        $userID = (int) $userID;
        $projectID = (int) $projectID;
        $subscribeService = $this->ioc->create('ISubscribeService');
        if (!$subscribeService->isRequestExists($userID, $projectID)) {
            $this->db->createCommand()
                    ->insert(
                            SubscribeRequestTable::NAME, 
                            array(
                                SubscribeRequestTable::SUBSCR_RQST_ID_FIELD => 0,
                                SubscribeRequestTable::USER_ID_FIELD => $userID,
                                SubscribeRequestTable::PROJ_ID_FIELD => $projectID,
                                SubscribeRequestTable::RQST_TM_FIELD => date("Y-m-d H:i:s")
                            )
                    );
        }
    }

}

?>