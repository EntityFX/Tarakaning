<?php
    require_once "engine/system/AEnum.php";
    
    final class ErrorStatusENUM extends AEnum
    {
        const IS_NEW        = "NEW";
        const ASSIGNED      = "ASSIGNED";
        const CONFIRMED     = "CONFIRMED";
        const SOLVED        = "SOLVED";
        const CLOSED        = "CLOSED";
    }
?>
