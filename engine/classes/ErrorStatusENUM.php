<?php
    require_once "AEnumChecker.php";
    
    final class ErrorStatusENUM extends AEnumChecker
    {
        const IS_NEW        = 1;
        const ASSIGNED      = 2;
        const CONFIRMED     = 3;
        const SOLVED        = 4;
        const CLOSED        = 5;
    }
?>
