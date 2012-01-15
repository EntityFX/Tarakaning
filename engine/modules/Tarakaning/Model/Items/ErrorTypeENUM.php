<?php

Loader::LoadSystem('AEnum'); 
    
final class ErrorTypeENUM extends AEnum
{
    const CRASH             = 'Crash';
    const COSMETIC          = 'Cosmetic';
    const ERROR_HANDLE      = 'Error Handling';
    const FUNCTIONAL        = 'Functional';
    const MINOR             = 'Minor';
    const MAJOR             = 'Major';
    const SETUP             = 'Setup';
    const BLOCK             = 'Block';
            
    public function __construct($value=self::ERROR_HANDLE)
    {
        parent::__construct($value);
    }
    
    public function getNormalized()
    {
        return array(
        	self::CRASH => "Крах",
        	self::COSMETIC => "Косметическая",
        	self::ERROR_HANDLE => "Исключение",
        	self::FUNCTIONAL => "Функциональня",
        	self::MINOR => "Неначительная",
        	self::MAJOR => "Значительная",
        	self::SETUP => "Ошибка инсталляции",
        	self::BLOCK => "Блокирующая"
        );
    }
}  
?>
