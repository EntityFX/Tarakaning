<?php 
require_once "engine/config/databaseConsts.php";
require_once 'engine/classes/ProjectsController.php';
$var = "3";
$nn = (int)$var;
echo $nn;

$n = new ProjectsController();
//var_dump();
$userID = 1;
$projectID = 11;
$projectName = "����� ������ 3";
$description = "��������";
$newDescription = "����� ��������"; 
$projectNewName = "����� ���";
/*
die("INSERT INTO `Projects` ( `ProjectID` , `Name` , `Description` , `OwnerID`, `CreateDate`)
			VALUES ('', '$projectName', '$description', '$userID', '". date("c")."');");*/

//$r2 = $n->addProject($userID, $projectName, $description);
//var_dump($n->setProjectName($userID, $projectNewName, $projectID));
//die(var_dump($r2));
?>
