<?php
    require_once "AEnumChecker.php";
    
    final class ErrorTypeENUM extends AEnumChecker
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
            $this->__value=$value;
        }
    }  
?>
