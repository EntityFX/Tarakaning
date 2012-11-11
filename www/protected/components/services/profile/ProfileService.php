<?php

class ProfileService extends ServiceBase implements IProfileService{

    /**
     * Set default project for current user
     * 
     * @param int $userId
     * @param int $projectID
     * @throws Exception 
     */
    public function setDefaultProject($userId, $projectID = NULL) {
        if ($projectID != NULL) {
            $projectID = (int) $projectID;
        }
        $projectService = $this->ioc->create('IProjectService');
        if ($projectService->existsById($projectID)) {
            $requestService = $this->ioc->create('IRequestService');
            $userId = (int) $userId;
            if ($requestService->isSubscribed($userId, $projectID) || $projectService->getOwnerID($projectID) == $userId) {
                $this->update($userId, $projectID);
            } else {
                throw new ServiceException("Пользователь не подписан на проект");
            }
        } else {
            throw new ServiceException("Проект не существует. Зверский хак!", 4);
        }
    }

    public function deleteDefaultProject($userId) {
        $this->update($userId, null);
    }
    
    private function update($userId, $value)
    {
        $this->db->createCommand()->update(
                self::$authTableName, array('DFLT_PROJ_ID' => $value), 'USER_ID = :id', array(':id' => $userId)
        );
    }

}

?>
