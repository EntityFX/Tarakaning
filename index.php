<?php

    require_once "engine/classes/UsersController.php";
    require_once "engine/classes/UserAuth.php"; 
    require_once "engine/classes/ConcreteUser.php";
    require_once "engine/classes/ProjectsController.php";
    
    $cUser=new ConcreteUser(); 
    /*$f=new ProjectsController();
    
    */
    //$f=new UserAuth();
    //$f->logIn("Vasya","biohazard");
    //$f->logOut();
    
    $cUser->setDefaultProject(11);
    //$cUser->deleteDefaultProject();
?>
