<?php
    require_once "engine/system/AEnum.php";
    
    final class ProjectFieldsUsersInfoENUM extends AEnum
    {
        const NICK_NAME  = "NickName";
        const OWNER      = "CountErrors";
        
        public function __construct($value=self::NICK_NAME)
        {
            parent::__construct($value);
        }
    } 
?>