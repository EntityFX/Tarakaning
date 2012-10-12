<?php

class ProfileService extends ServiceBase {

    public function setDefaultProject($userId, $projectID = NULL) {
        if ($projectID != NULL) {
            $projectID = (int) $projectID;
        }
        $projectService = new ProjectService();
        if ($projectService->existsById($projectID)) {
            $requestService = new RequestService();
            $userId = (int) $userId;
            if ($requestService->isSubscribed($userId, $projectID) || $projectService->getOwnerID($projectID) == $userId) {
                $this->update($userId, $projectID);
            } else {
                throw new Exception("Пользователь не подписан на проект");
            }
        } else {
            throw new Exception("Проект не существует. Зверский хак!", 4);
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
