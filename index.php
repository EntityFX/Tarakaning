<?php
    
    /**
    * @global Const Путь к файлам
    * @name SOURCE_PATH
    */
    define("SOURCE_PATH","");
    
    /**
    * Подключает константы БД
    * @filesource config/databaseConsts.php 
    */    
    require_once SOURCE_PATH."engine/config/globals.php";    
    
    require_once SOURCE_PATH."engine/kernel/FrontController.php";
    
    /**
    * Важный экземпляр класса
    * 
    * @var Kernel
    */
    $kernel=FrontController::getInstance();
    //FrontController::setGlobalEncoding("UTF-8");
    $kernel->run();    
?>
