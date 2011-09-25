<?php
    require_once "engine/system/AEnum.php";
    
    final class ItemCommentsENUM extends AEnum
    {
        const NICK_NAME  = "NickName";
        const TIME       = "Time";
        const COMMENT    = "Comment";
        
        public function __construct($value=self::NICK_NAME)
        {
            parent::__construct($value);
        }
    } 
?>