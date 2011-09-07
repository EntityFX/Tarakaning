<?php
    require_once "engine/system/AEnum.php";
    
    final class ErrorStatusENUM extends AEnum
    {
        const IS_NEW        = "NEW";
        const IDENTIFIED      = "IDENTIFIED";
        const ASSESSED     = "ASSESSED";
        const RESOLVED        = "RESOLVED";
        const CLOSED        = "CLOSED";
        
        public function __construct($value=self::IS_NEW)
        {
            parent::__construct($value);
        }
    }
?>
