<?php
require_once 'InfoBasePage.php';
require_once 'engine/modules/Tarakaning/Logic/CommentsController.php';
require_once 'engine/modules/Tarakaning/Logic/ErrorReportsController.php';
require_once 'engine/modules/Tarakaning/Controls/TarakaningULListPager.php';
require_once 'engine/libs/controls/Orderer/Orderer.php';

class BugPage extends InfoBasePage 
{
	private $_bugData;
	
	private $_projectsList;
	
	private $_commentData;
	
	private $_commentsController;
	
	private $_userData;
	
	private $_commentsPaginator;
	
	private $_orderer;
	
	private $_orderData;
	
	private $_canEditReport=false;
	
	private $_canCloseReport;
	
	protected function onInit()
	{
		parent::onInit();

		$this->_userData=$this->_controller->auth->getName();
		$projectsController=new ProjectsController();
		$this->_projectsList=$projectsController->getUserProjects($this->_userData["UserID"]);
		
		
		$bugsOperation=new ErrorReportsController(
			$this->_userData["DefaultProjectID"] == null ? $this->_projectsList[0]['ProjectID'] : $this->_userData["DefaultProjectID"],
			$this->_userData["UserID"]
		);
		if (isset($this->_parameters[0]))
		{
			$this->_bugData=$bugsOperation->getReport($this->_parameters[0]);
			$this->_canCloseReport=$bugsOperation->canClose($this->_parameters[0]);
			if (!$this->_canCloseReport && $this->_bugData["Status"]==ErrorStatusENUM::CLOSED)
			{
				$this->_canEditReport=false;
			}
			else 
			{
				$this->_canEditReport=$bugsOperation->canEditReport($this->_parameters[0]);
			}
		}
		
		if ($this->_bugData==null)
		{
			$this->navigate("/my/bugs/");
		}
		
		$this->_commentsController=new CommentsController();
		
		if ($this->request->isPost() )
		{
			$postData=$this->request->getParams();
			if ($this->_bugData!=null);
			{
				if ($postData['sendComment']!=null)
				{
					try
					{
						$this->_commentsController->setReportComment(
							$this->_bugData['ProjectID'], 
							$this->_userData["UserID"], 
							$this->_bugData['ID'], 
							$postData['comment']
						);
					}
					catch (Exception $exception)
					{
						$error = array(
							"error" => $exception,
							"postData" => $postData
						);
						$this->_controller->error->addError("addCommentError",$error);
					}
				}
				else if ($postData['cnange_state']!=null)
				{
					$stateEnum=new ErrorStatusENUM($postData['state']);
					$editResult=$bugsOperation->editReport(
						$this->_bugData['ID'], 
						$stateEnum, 
						$this->_userData["UserID"]
					);
					if ($editResult) $this->_bugData["Status"]=$stateEnum->getValue();
				}
			}
		}

		if ($this->request->isPost())
		{
			if ($this->request->getPost("del",null)!=null)
			{
				$this->deleteSelectedItems();
			}
		}
		if ($this->_bugData!=null)
		{
			$this->_commentsPaginator=new TarakaningULListPager(
				$this->_commentsController->getReportCommentsCount(				
					$this->_bugData['ID']
				)
			);
			$this->_orderer=new Orderer(new ItemCommentsENUM());
			$this->_orderData=$this->_orderer->getNewUrls();
			$this->_commentsPaginator->setIDTag('comments');
			$this->_commentsData=$this->_commentsController->getReportComments(
				$this->_bugData['ProjectID'], 
				$this->_bugData['ID'], 
				$this->_bugData['UserID'],
				new ItemCommentsENUM($this->_orderer->getOrderField()),
				new MySQLOrderENUM($this->_orderer->getOrder()),
				$this->_commentsPaginator->getOffset(),
				$this->_commentsPaginator->getSize()
			);
		}
	}
	
	protected function doAssign()
	{
		parent::doAssign();
		if ($this->_bugData!=null)
		{
			$this->_smarty->assign("BUG",$this->_bugData);
			$this->_smarty->assign("COMMENTS",$this->_commentsData);
			$this->_smarty->assign("COMMENTS_ORDER",$this->_orderData);
			$this->_smarty->assign("COMMENTS_PAGINATOR",$this->_commentsPaginator->getHTML());
			$this->_smarty->assign("CAN_EDIT_REPORT",$this->_canEditReport);
			$itemStatuses=new ErrorStatusENUM($this->_bugData['Status']);
			$this->_smarty->assign(
				"STATUSES",
				array(
					'values' => $itemStatuses->getStates($itemStatuses,$this->_canCloseReport),
					'selected' => $itemStatuses->getValue()
				)
			);
			$this->_smarty->assign("USER_ID",$this->_userData["UserID"]);
			$addCommentError=$this->_controller->error->getErrorByName("addCommentError");
			if ($addCommentError!=null)
			{
				$exception=$addCommentError["error"];
				$this->_smarty->assign("ERROR",$exception->getMessage());
				$this->_smarty->assign("DATA",$addCommentError["postData"]);
			}
		}
	}
	
	protected function deleteSelectedItems()
	{
		$checkboxes=$this->request->getPost("del_i");
		$this->_commentsController->deleteCommentsFromList(
			$this->_userData["UserID"],
			$checkboxes
		);
	}
}
?>