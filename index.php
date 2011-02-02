<?php

    require_once "engine/classes/UsersController.php";
    require_once "engine/classes/UserAuth.php"; 
    require_once "engine/classes/ConcreteUser.php";
    require_once "engine/classes/ProjectsController.php";
    require_once "engine/classes/ErrorPriorityENUM.php"; 
    require_once "engine/classes/ErrorReportsController.php";
     
    $cUser=new ConcreteUser(); 
    $cUser->setDefaultProject(2);
    $f=new ProjectsController();
    
    //$f=new UserAuth();
    //$f->logIn("Vasya","helloworld");
    //$f->logOut();
    
    //$cUser->setDefaultProject(1);
    //$cUser->deleteDefaultProject();
    //$en=new ErrorPriorityENUM(675675);
    //var_dump($en->getValue(),$en->check());
    $erc=new ErrorReportsController();
    //$erc->editReport(17,new ErrorStatusENUM(ErrorStatusENUM::SOLVED));
    var_dump($erc->getReportsByProject());
    /*$erc->addReport(
        new ErrorPriorityENUM(),
        new ErrorStatusENUM(ErrorStatusENUM::IS_NEW),
        new ErrorTypeENUM(),
        "Возник BSOD",
        "При попытке вызвать экран, вышла критическая ошибка",
        ""
    ); */
    
?>
