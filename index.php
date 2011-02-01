<?php

    require_once "engine/classes/UsersController.php";
    require_once "engine/classes/UserAuth.php"; 
    require_once "engine/classes/ConcreteUser.php";
    require_once "engine/classes/ProjectsController.php";
    require_once "engine/classes/ErrorPriorityENUM.php"; 
    require_once "engine/classes/ErrorReportsController.php";
     
    $cUser=new ConcreteUser(); 
    /*$f=new ProjectsController();
    
    */
    $f=new UserAuth();
    //$f->logIn("Vasya","helloworld");
    //$f->logOut();
    
    //$cUser->setDefaultProject(1);
    //$cUser->deleteDefaultProject();
    //$en=new ErrorPriorityENUM(675675);
    //var_dump($en->getValue(),$en->check());
    $erc=new ErrorReportsController(1);
?>
