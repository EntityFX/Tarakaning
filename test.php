<?php 
require_once "engine/config/databaseConsts.php";
require_once 'engine/classes/ProjectsController.php';
require_once 'engine/classes/Subscribes.php';
require_once 'engine/classes/SubscribesController.php';

$userID = 1;
$projectID = 19;
$requestID = 1;



/*$s = new SubscribesController();
var_dump($s->getRequests($userID, $projectID));*/

$n = new ProjectsController();
var_dump($n->isProjectExists($projectID));
/*
$projectName = "какой проект 3";
$description = "описание";
$newDescription = "новое описание"; 
$projectNewName = "новое имя";*/


?>
