<?php
    require_once "engine/system/AEnum.php";
    
    final class ErrorTypeENUM extends AEnum
    {
        const CRASH             = 1;
        const COSMETIC          = 2;
        const ERROR_HANDLE      = 3;
        const FUNCTIONAL        = 4;
        const MINOR             = 5;
        const MAJOR             = 6;
        const SETUP             = 7;
        const BLOCK             = 8;
                
        public function __construct($value=self::CRASH)
        {
            parent::__construct($value);
        }
    }  
?>
