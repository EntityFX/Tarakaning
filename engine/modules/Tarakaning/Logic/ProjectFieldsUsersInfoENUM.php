<?php
    require_once SOURCE_PATH."engine/system/AEnum.php";
    
    final class ProjectFieldsUsersInfoENUM extends AEnum
    {
        const NICK_NAME     = "Nick";
        const OWNER         = "CountErrors";
        const COUNT_CREATED = "CountCreated";
        
        public function __construct($value=self::NICK_NAME)
        {
            parent::__construct($value);
        }
    } 
?>