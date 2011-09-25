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
	
	private $_userData;
	
	private $_commentsPaginator;
	
	private $_orderer;
	
	private $_orderData;
	
	protected function onInit()
	{
		parent::onInit();
		$reportCommentsOperation=new CommentsController();
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
		}
		else 
		{
			$this->navigate("/my/bugs/");
		}
		if ($this->request->isPost() )
		{
			$postData=$this->request->getParams();
			if ($postData['sentComment']!=null && $this->_bugData!=null);
			{
				try
				{
					$reportCommentsOperation->setReportComment(
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
		}
		try
		{
			$this->_commentsPaginator=new TarakaningULListPager(
				$reportCommentsOperation->getReportCommentsCount(				
					$this->_bugData['ID']
				)
			);
			$this->_orderer=new Orderer(new ItemCommentsENUM());
			$this->_orderData=$this->_orderer->getNewUrls();
			$this->_commentsPaginator->setIDTag('comments');
			$this->_commentsData=$reportCommentsOperation->getReportComments(
				$this->_bugData['ProjectID'], 
				$this->_bugData['ID'], 
				$this->_bugData['UserID'],
				new ItemCommentsENUM($this->_orderer->getOrderField()),
				new MySQLOrderENUM($this->_orderer->getOrder()),
				$this->_commentsPaginator->getOffset(),
				$this->_commentsPaginator->getSize()
			);
		}
		catch (Exception $exception)
		{
		}
	}
	
	protected function doAssign()
	{
		parent::doAssign();
		$this->_smarty->assign("BUG",$this->_bugData);
		$this->_smarty->assign("COMMENTS",$this->_commentsData);
		$this->_smarty->assign("COMMENTS_ORDER",$this->_orderData);
		$this->_smarty->assign("COMMENTS_PAGINATOR",$this->_commentsPaginator->getHTML());
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
?>