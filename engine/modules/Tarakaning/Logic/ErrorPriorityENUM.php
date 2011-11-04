<?php
    require_once SOURCE_PATH."engine/system/AEnum.php";
    
    final class ErrorPriorityENUM extends AEnum
    {
        const MINIMAL   = 0;
        const NORMAL    = 1;
        const HIGH      = 2;
        
        public function getNormalized()
        {
        	return array(
        		self::MINIMAL => "������",
        		self::NORMAL  => "�������",
        		self::HIGH    => "������",
        	);
        }
    }  
?>
