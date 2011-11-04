<?php
    require_once SOURCE_PATH."engine/system/AEnum.php";
    
    final class ErrorFieldsENUM extends AEnum
    {
        const ID         = "ID";
        const OWNER      = "UserID";
        const KIND       = "Kind";
        const NICK_NAME  = "NickName";
        const ASSIGNED_NICK_NAME  = "AssignedNickName";
        const STATUS     = "Status";
        const TITLE      = "Title";
        const PRIORITY   = "PriorityLevel";
        const TYPE       = "ErrorType";
        const TIME       = "Time";
        
        public function __construct($value=self::ID)
        {
            parent::__construct($value);
        }
    } 
?>
