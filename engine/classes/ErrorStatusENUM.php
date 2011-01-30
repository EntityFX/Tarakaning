<?php
    require_once "AEnumChecker.php";
    
    final class ErrorStatusENUM extends AEnumChecker
    {
        const IS_NEW=0;
        const ASSIGNED=1;
        const CONFIRMED=2;
        const SOLVED=3;
        const CLOSED=4;
    }
?>
