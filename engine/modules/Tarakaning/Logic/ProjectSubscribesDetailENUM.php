<?php
    require_once SOURCE_PATH."engine/system/AEnum.php";
    
	class ProjectSubscribesDetailENUM extends AEnum
	{
        const NICK_NAME     	= "NickName";
        const REQUEST_TIME      = "RequestTime";
        
        public function __construct($value=self::NICK_NAME)
        {
            parent::__construct($value);
        }
	}