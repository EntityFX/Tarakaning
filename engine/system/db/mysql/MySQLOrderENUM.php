<?php
    require_once SOURCE_PATH."engine/system/AEnum.php";
    
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
