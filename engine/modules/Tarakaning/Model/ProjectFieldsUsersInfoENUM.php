<?php

Loader::LoadSystem('AEnum'); 
    
final class ProjectFieldsUsersInfoENUM extends AEnum
{
    const NICK_NAME     = "NickName";
    const OWNER         = "CountErrors";
    const COUNT_CREATED = "CountCreated";
    
    public function __construct($value=self::NICK_NAME)
    {
        parent::__construct($value);
    }
} 
?>