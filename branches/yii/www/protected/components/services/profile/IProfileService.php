<?php

/**
 *
 * @author EntityFX
 */
interface IProfileService {
     /**
     * Set default project for current user
     * 
     * @param int $userId
     * @param int $projectID
     * @throws Exception 
     */
    function setDefaultProject($userId, $projectID = NULL);
}

?>
