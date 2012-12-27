<?php

/**
 * Description of UserWrapper
 *
 * @author EntityFX
 */
class UserWrapper {

    public static function getUsers(array &$projectUsersList) {
        foreach ($projectUsersList as $userItem) {
            $result[(int) $userItem["UserID"]] = $userItem["NickName"];
        }
        return $result;
    }

    /**
     * Prepares list with user projects for dropdown
     * 
     * @param array $userProjetsList
     * @return array 
     */
    public static function prepareUserProjectsListData(array $userProjetsList) {
        if ($userProjetsList == null) {
            return array();
        } else {
            return CHtml::listData(
                            $userProjetsList, 'ProjectID', 'Name'
            );
        }
    }

}

?>
