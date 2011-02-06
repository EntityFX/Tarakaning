<?php 
require_once "engine/config/databaseConsts.php";
require_once 'engine/classes/ProjectsController.php';
require_once 'engine/classes/Subscribes.php';
require_once 'engine/classes/RequestsController.php';
require_once 'engine/classes/CommentsController.php';

require_once 'engine/classes/CheckParams.php';

$vv = new CheckParams();
$paramArray["userID"] = 1;
$paramArray["projectID"] = 1; 
$paramArray["historyID"] = 144; 
try 
{
	$vv->checkExisting($paramArray);
} 
catch (Exception $e) 
{
	die($e->getMessage());
}
die("���� ������ �������");
$userID = 1;
$projectID = 9;
$requestID = 1;

$c = new Subscribes();
$commentID = 1;

var_dump($c->getProjectUsers($projectID));

/*$s = new SubscribesController();
var_dump($s->getRequests($userID, $projectID));*/

/*$n = new ProjectsController();
var_dump($n->getProjects());*/
/*
$projectName = "����� ������ 3";
$description = "��������";
$newDescription = "����� ��������"; 
$projectNewName = "����� ���";*/


?>
