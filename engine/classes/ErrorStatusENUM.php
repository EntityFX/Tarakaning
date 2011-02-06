<?php
    require_once "AEnumChecker.php";
    
    final class ErrorStatusENUM extends AEnumChecker
    {
        const IS_NEW        = "NEW";
        const ASSIGNED      = "ASSIGNED";
        const CONFIRMED     = "CONFIRMED";
        const SOLVED        = "SOLVED";
        const CLOSED        = "CLOSED";
    }
?>
