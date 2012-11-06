<?php

require_once 'ProjectsController.php';
require_once 'ReportHistoryController.php';

	class CheckParams
	{
		public function checkExisting($paramArray)
		{
			foreach ($paramArray as $key => $value) 
			{
				if ($key == "projectID") 
				{
					$p = new ProjectService();
					$value = (int)$value;
					if(!$p->existsById($value)) throw new Exception("Проект не существует.",101);
					
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