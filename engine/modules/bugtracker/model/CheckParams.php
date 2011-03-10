<?php

require_once 'engine/classes/ProjectsController.php';
require_once 'engine/classes/ReportHistory.php';

	class CheckParams
	{
		public function checkExisting($paramArray)
		{
			foreach ($paramArray as $key => $value) 
			{
				if ($key == "projectID") 
				{
					$p = new ProjectsController();
					$value = (int)$value;
					if(!$p->isProjectExists($value)) throw new Exception("Проект не существует.",101);
					
				}
				/*
				 * тут проверка на существование юзера. именно тут.
				 */
				
				
				if ($key == "historyID")
				{
					$h = new ReportHistory();
					$value = (int)$value;
					$d = $h->isOwner($paramArray["userID"], $value, $paramArray["projectID"]);
					if(!$d) throw new Exception("Вы не являетесь автором или админом.", 901);
					
					if(!$h->isHistoryIdExists($value)) throw new Exception("Элемента с таким номером нет.", 902);
				}
			}
			
		}
	}

?>