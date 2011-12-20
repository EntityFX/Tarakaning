<?php
    require_once SOURCE_PATH."engine/system/AEnum.php";
    
    final class MyProjectsFieldsENUM extends AEnum
    {
        const PROJECT_NAME  	= "ProjectName";
        const DESCRIPTION      	= "Description";
        const COUNT_USERS      	= "CountUsers";
        const COUNT_REQUESTS    = "CountSubscribeRequests";
        const CREATE_DATE      	= "CreateDate";
        const NICK_NAME      	= "OwnerNickName";
                                        
        public function __construct($value=self::PROJECT_NAME)
        {
            parent::__construct($value);
        }
    } 
?>