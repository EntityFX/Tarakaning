<?php 

require_once 'ProjectsController.php';

$n = new ProjectsController();
//var_dump();
$userId = 1;
$projectName = "��� ����� ������";
$description = "��������"; 
$r = $n->addProject($userId, $projectName, $description);
die(var_dump($r));
?>