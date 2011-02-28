<?php
    require_once "engine/system/AEnum.php";
    
    final class MySQLOrderENUM extends AEnum
    {
        const ASC   = "ASC";
        const DESC  = "DESC";
                
        public function __construct($value=self::ASC)
        {
            $this->__value=$value;
        }
    } 
?>
