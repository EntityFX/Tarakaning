<?php
    require_once "engine/system/AEnum.php";
    
    final class ErrorTypeENUM extends AEnum
    {
        const CRASH             = 'Crash';
        const COSMETIC          = 'Cosmetic';
        const ERROR_HANDLE      = 'Error Handling';
        const FUNCTIONAL        = 'Functional';
        const MINOR             = 'Minor';
        const MAJOR             = 'Major';
        const SETUP             = 'Setup';
        const BLOCK             = 'Block';
                
        public function __construct($value=self::ERROR_HANDLE)
        {
            parent::__construct($value);
        }
    }  
?>
