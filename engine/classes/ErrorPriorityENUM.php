<?php
    require_once "AEnumChecker.php";
    
    final class ErrorPriorityENUM extends AEnumChecker
    {
        const MINIMAL=0;
        const NORMAL=1;
        const HIGH=2;
                
        public function __construct($value=self::NORMAL)
        {
            $this->__value=$value;
        }
    }  
?>
