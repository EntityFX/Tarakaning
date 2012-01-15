<?php
	class ItemsFacade
	{
		const ADD_ITEM="������ ���������";
		const STATUS_CHANGE="������������ <strong>%s</strong> ������� ������ � <strong>%s</strong> �� <strong>%s</strong>";
		
		/**
		 * 
		 * ������ ����������� �������
		 * @var ErrorReportsController
		 */
		private $_itemsController;
		
		/**
		 * 
		 * ������������ � �������
		 * @var UserAuth
		 */
		private  $_concreteUser;
		
		/**
		 * 
		 * ������ ����������� ������� �������
		 * @var ReportHistoryController
		 */
		private $_historyController;
		
		private $_userID;
		
		private $_projectID;
		
		public function __construct(ItemsModel $itemsController, ItemsHistoryModel $historyController, UserAuth $user, $projectID)
		{
			$this->_itemsController=$itemsController;
			$this->_historyController=$historyController;
			$this->_concreteUser=$user;
			$userData=$this->_concreteUser->getName();
			$this->_userID=(int)$userData["USER_ID"];
			$this->_projectID=(int)$projectID;
		}
		
		public function addItem(ItemDBKindENUM $kind, ErrorPriorityENUM $priority, ErrorStatusENUM $errorStatus, ErrorTypeEnum $type, $title, $description="", $steps="", $assignedTo=null)
		{
			$itemID=(int)$this->_itemsController->addReport($kind, $priority, $errorStatus, $type, $title, $description, $steps, $assignedTo);
			$this->_historyController->addHistory($this->_userID,$itemID,self::ADD_ITEM);
			return $itemID;
		}
		
		public function editReport($reportID,ErrorStatusENUM $newStatus, $newBugsData)
		{
			$reportDataBefore=$this->_itemsController->getReport($reportID);
			$errorEnumBefore=new ErrorStatusENUM($reportDataBefore["Status"]);
			if ($newBugsData["DefectType"]==null)
			{
				$newBugsData["DefectType"]=ErrorTypeENUM::MAJOR;
			}
			$res=$this->_itemsController->editReport(
				$reportID, 
				$this->_userID, 
				$this->_projectID, 
				$newBugsData["Title"],
				$newStatus, 
				new ErrorPriorityENUM($newBugsData["PriorityLevel"]),
				new ErrorTypeENUM($newBugsData["DefectType"]),
				$newBugsData["Description"],
				$newBugsData["StepsText"],
				$newBugsData["AssignedTo"]
			);
			$this->_historyController->addHistory(
				$this->_userID, 
				$reportID, 
				sprintf(
					self::STATUS_CHANGE,
					$this->_userID,
					$errorEnumBefore->getLocaleValue(),
					$newStatus->getLocaleValue()
				)
			);
			return $res;
		}
	}