<?php

    require_once "engine/classes/UsersController.php";
    require_once "engine/classes/UserAuth.php"; 
    require_once "engine/classes/ConcreteUser.php";
    require_once "engine/classes/ProjectsController.php";
    require_once "engine/classes/ErrorPriorityENUM.php"; 
    require_once "engine/classes/ErrorReportsController.php";
     
    $c=new UsersController();
    //$c->activateUser(0);
    //$c->createUser("Vasiliy","fuckfuck",0,"����","�������","����������","tym_@mail.ru");      
    $f=new UserAuth();
    //$f->logIn("Vasiliy","fuckfuck");

    $cUser=new ConcreteUser(); 
    //$cUser->setDefaultProject(10);
    $f=new ProjectsController();
    //$f->logOut();
    
    //$cUser->setDefaultProject(1);
    //$cUser->deleteDefaultProject();
    //$en=new ErrorPriorityENUM(675675);
    //var_dump($en->getValue(),$en->check());
    $erc=new ErrorReportsController(2);
    $erc->editReport(2,new ErrorStatusENUM(ErrorStatusENUM::IS_NEW),$cUser->id);
    //$erc->editReport(17,new ErrorStatusENUM(ErrorStatusENUM::SOLVED),$cUser-);
    /*$erc->addReport(
        new ErrorPriorityENUM(),
        new ErrorStatusENUM(ErrorStatusENUM::IS_NEW),
        new ErrorTypeENUM(),
        "������ BSOD",
        "��� ������� ������� �����, ����� ����������� ������",
        ""
    );*/
    
?>
