<?php

class ProfileService extends ServiceBase implements IProfileService {

    /**
     * Set default project for current user
     * 
     * @param int $userId
     * @param int $projectID
     * @throws Exception 
     */
    public function setDefaultProject($userId, $projectID) {
        if ($projectID != NULL) {
            $projectID = (int) $projectID;
        }
        $projectService = $this->ioc->create('IProjectService');
        if ($projectService->existsById($projectID)) {
            $requestService = $this->ioc->create('IRequestService');
            $userId = (int) $userId;

            $this->tryCheckIsSubscribedOrOwner($userId, $projectID);

            $this->update($userId, $projectID);
        } else {
            throw new ServiceException("Проект не существует. Зверский хак!", 4);
        }
    }

    public function deleteDefaultProject($userId) {
        $this->update($userId, null);
    }

    private function update($userId, $value) {
        $this->db->createCommand()->update(
                UserTable::NAME, 
                array(UserTable::DFLT_PROJ_ID_FIELD => $value), 
                UserTable::USER_ID_FIELD . ' = :id', 
                array(':id' => $userId)
        );
    }

}

?>
