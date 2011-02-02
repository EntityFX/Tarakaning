<?php
    require_once "AEnumChecker.php";
    
    final class ErrorPriorityENUM extends AEnumChecker
    {
        const MINIMAL   = 1;
        const NORMAL    = 2;
        const HIGH      = 3;
                
        public function __construct($value=self::NORMAL)
        {
            $this->__value=$value;
        }
    }  
?>
