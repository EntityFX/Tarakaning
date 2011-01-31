<?php
    require_once "AEnumChecker.php";
    
    final class ErrorTypeENUM extends AEnumChecker
    {
        const CRASH=0;
        const COSMETIC=1;
        const ERROR_HANDLE=2;
        const FUNCTIONAL=3;
        const MINOR=4;
        const MAJOR=5;
        const SETUP=6;
        const BLOCK=7;
                
        public function __construct($value=self::NORMAL)
        {
            $this->__value=$value;
        }
    }  
?>
