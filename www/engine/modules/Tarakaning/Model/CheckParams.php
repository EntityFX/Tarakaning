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
					$p = new ProjectsModel();
					$value = (int)$value;
					if(!$p->isProjectExists($value)) throw new Exception("������ �� ����������.",101);
					
				}
				/*
				 * ��� �������� �� ������������� �����. ������ ���.
				 */
				
				
				if ($key == "historyID")
				{
					$h = new ReportHistory();
					$value = (int)$value;
					$d = $h->isOwner($paramArray["userID"], $value, $paramArray["projectID"]);
					if(!$d) throw new Exception("�� �� ��������� ������� ��� �������.", 901);
					
					if(!$h->isHistoryIdExists($value)) throw new Exception("�������� � ����� ������� ���.", 902);
				}
			}
			
		}
	}

?>