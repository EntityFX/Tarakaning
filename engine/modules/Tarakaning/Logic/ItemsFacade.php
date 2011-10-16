<?php
require_once 'ReportHistoryController.php';

	class ItemsFacade
	{
		const ADD_ITEM="������ ���������";
		const STATUS_CHANGE="������������ %s ������� ������ � %s �� %s";
		
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
		
		public function __construct(ErrorReportsController $itemsController, ReportHistoryController $historyController, UserAuth $user)
		{
			$this->_itemsController=$itemsController;
			$this->_historyController=$historyController;
			$this->_concreteUser=$user;
		}
		
		public function addItem(ItemDBKindENUM $kind, ErrorPriorityENUM $priority, ErrorStatusENUM $errorStatus, ErrorTypeEnum $type, $title, $description="", $steps="", $assignedTo=null)
		{
			try
			{
				$itemID=(int)$this->_itemsController->addReport($kind, $priority, $errorStatus, $type, $title, $description, $steps, $assignedTo);
			}
			catch (Exception $ex)
			{
				throw $ex;
			}
			$userData=$this->_concreteUser->getName();
			$userID=(int)$userData["UserID"];
			$this->_historyController->addHistory($itemID, $this->_concreteUser->id, $this->getAddStatus());
			return $itemID;
		}
		
		private function getAddStatus()
		{
			return self::ADD_ITEM;
		}
	}