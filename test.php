<?php 
require_once "engine/config/databaseConsts.php";
require_once 'engine/classes/ProjectsController.php';
var_dump(mysql_connect("localhost","root","")); echo "<br />";



$n = new ProjectsController();
//var_dump();
$userId = 1;
$projectName = "��� ����� ������";
$description = "��������"; 
$r2 = $n->addProject($userId, $projectName, $description);
die(var_dump($r2));
?>
