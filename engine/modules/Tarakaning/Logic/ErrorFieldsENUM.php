<?php
    require_once "engine/system/AEnum.php";
    
    final class ErrorFieldsENUM extends AEnum
    {
        const ID         = "ID";
        const OWNER      = "UserID"; 
        const STATUS     = "Status";
        const TITLE      = "Title";
        const PRIORITY   = "PriorityLevel";
        const TYPE       = "ErrorType";
        const TIME       = "Time";
    } 
?>
