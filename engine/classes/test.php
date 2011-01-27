<?php 

require_once 'ProjectsController.php';

$n = new ProjectsController();
//var_dump();
$userId = 1;
$projectName = "Вах какой проект";
$description = "описание"; 
$r = $n->addProject($userId, $projectName, $description);
die(var_dump($r));
?>