<?php
    
    /**
    * @global Const ���� � ������
    * @name SOURCE_PATH
    */
    define("SOURCE_PATH",dirname(__FILE__).'/');
    
    /**
    * ���������� ��������� ��
    * @filesource config/databaseConsts.php 
    */    
    require_once SOURCE_PATH."engine/config/globals.php";    
    
    require_once SOURCE_PATH."engine/kernel/FrontController.php";
    
    /**
    * ������ ��������� ������
    * 
    * @var Kernel
    */
    $kernel=FrontController::getInstance();
    //FrontController::setGlobalEncoding("UTF-8");
    $kernel->run();    
?>
