<?php

Loader::LoadPageController('InfoBasePage');  

Loader::LoadModel('Items/ItemsModel'); 
Loader::LoadModel('Comments/CommentsModel');
Loader::LoadModel('Items/ItemsFacade');   
Loader::LoadModel('Items/ItemsHistoryModel'); 
Loader::LoadModel('Items/ItemCommentsENUM'); 
Loader::LoadModel('Items/ErrorPriorityENUM');    
Loader::LoadModel('Items/ItemKindENUM');    
Loader::LoadModel('Items/ErrorTypeENUM');    
Loader::LoadModel('Items/ErrorStatusENUM');
Loader::LoadModel('Items/ItemTableFieldsENUM');
Loader::LoadModel('Requests/RequestModel');  

Loader::LoadSystem('addons','Serialize');     

Loader::LoadControl('TarakaningULListPager');  
Loader::LoadSystem('controls','Orderer/Orderer');

class BugPage extends InfoBasePage 
{
	private $_bugData;
	
	private $_commentData;
	
	private $_commentsModel;
	
	private $_userData;
	
	private $_commentsPaginator;
    
    private $_commentsCount;
	
	private $_orderer;
	
	private $_orderData;
	
	private $_canEditStatus=false;
	
	private $_canEditData;
	
	private $_history;
	
	private $_historyData;
	
	private $_projectUsersList;
	
	private $_bugsOperation;
    
    private $_previousItemID;
    
    private $_nextItemID;
    
	
	protected function onInit()
	{
		parent::onInit();

		$this->_userData=$this->_controller->auth->getName();
		
		$this->_bugsOperation=new ItemsModel(
			$this->_userData["DefaultProjectID"] == null ? $this->_projectsList[0]['ProjectID'] : $this->_userData["DefaultProjectID"],
			$this->_userData["UserID"]
		);
		if (isset($this->_parameters[0]) && $this->_parameters[0]!=='')
		{
			$this->_bugData=$this->_bugsOperation->getReport($this->_parameters[0]);
            $itemProjectID=(int)$this->_bugData['ProjectID'];
            $itemID=(int)$this->_bugData["ID"];
            $this->_previousItemID=$this->_bugsOperation->getPreviousItemID($itemID,$itemProjectID);
            $this->_nextItemID=$this->_bugsOperation->getNextItemID($itemID,$itemProjectID);
            $this->_canEditData=$this->_bugsOperation->canEditData($this->_parameters[0],$this->_bugData['ProjectID']);
			if (!$this->_canEditData && $this->_bugData["Status"]==ErrorStatusENUM::CLOSED)
			{
				$this->_canEditStatus=false;
			}
			else 
			{
				$this->_canEditStatus=$this->_bugsOperation->canEditStatus($this->_parameters[0],$this->_bugData['ProjectID']);
			}
		}
		
		if ($this->_bugData==null)
		{
			$this->navigate("/my/bugs/");
		}
		$this->_history=new ItemsHistoryModel();
		$this->_commentsModel=new CommentsModel();
		
		if ($this->request->isPost() )
		{
			$postData=$this->request->getParams();
			if ($this->_bugData!=null);
			{
                if ($postData['sendComment']!=null)
				{
                    $this->addComment();
				}
			}

			if ($this->request->getPost("del",null)!=null)
			{
				$this->deleteSelectedItems();
			}
			else if ($this->request->getPost("cnange_state",null)==='')
			{
				$this->editState();
			}
		}
		
		if ($this->_bugData!=null)
		{
            $this->_commentsCount =  $this->_commentsModel->getReportCommentsCount($this->_bugData['ID']);
            $this->_commentsPaginator=new TarakaningULListPager($this->_commentsCount);
			$this->_orderer=new Orderer(new ItemCommentsENUM());
			$this->_orderData=$this->_orderer->getNewUrls();
			$this->_commentsPaginator->setIDTag('comments');
			$this->_commentsData=$this->_commentsModel->getReportComments(
				$this->_bugData['ProjectID'], 
				$this->_bugData['ID'], 
				$this->_bugData['UserID'],
				new ItemCommentsENUM($this->_orderer->getOrderField()),
				new MySQLOrderENUM($this->_orderer->getOrder()),
				$this->_commentsPaginator->getOffset(),
				$this->_commentsPaginator->getSize()
			);
            
			$usersList=$this->_projectsController->getProjectUsers($this->_bugData['ProjectID']);
            $this->_projectUsersList=$this->normalizeAssignUsersListForControl($usersList);
			$this->_historyData=$this->_history->getReportHistory($this->_bugData['ID'], new TarakaningULListPager(10));
		}
	}
	
	private function normalizeAssignUsersListForControl(&$data)
	{
		if ($data!=null)
		{
			foreach ($data as $value)
			{
				$res[$value['UserID']]=$value['NickName'];
			}
		}
		return $res;
	}
	
	protected function doAssign()
	{
		parent::doAssign();
		if ($this->_bugData!=null)  //item data information
		{
		    $this->_bugData['Title']=htmlspecialchars($this->_bugData['Title']);
            $this->_smarty->assign("BUG",$this->_bugData);
            $this->_smarty->assign('ITEM_PREV_ID',$this->_previousItemID);
            $this->_smarty->assign('ITEM_NEXT_ID',$this->_nextItemID);

			$this->_smarty->assign("USERS_ASSIGN_TO",$this->_projectUsersList);
            $this->_smarty->assign("COMMENT_COUNT",$this->_commentsCount);
            $this->_smarty->assign("COMMENTS",$this->_commentsData);
			$this->_smarty->assign("COMMENTS_ORDER",$this->_orderData);
			$this->_smarty->assign("COMMENTS_PAGINATOR",$this->_commentsPaginator->getHTML());

			$this->_smarty->assign("HISTORY",$this->_historyData);
			
			$this->_smarty->assign("CAN_EDIT_DATA",$this->_canEditData);
			$this->_smarty->assign("CAN_EDIT_STATUS",$this->_canEditStatus);
			$itemStatuses=new ErrorStatusENUM($this->_bugData['Status']);
			$this->_smarty->assign(
				"STATUSES",
				array(
					'values' => $itemStatuses->getStates($itemStatuses,$this->_canEditData),
					'selected' => $itemStatuses->getValue()
				)
			);
			
			if ($this->_bugData['Kind']==ItemKindENUM::DEFECT)
			{
				$defectType=new ErrorTypeENUM($this->_bugData['ErrorType']);

				$this->_smarty->assign(
					"DEFECT_TYPE",
					array(
						'values' => $defectType->getNormalized(),
						'selected' => $defectType->getValue()
					)
				);
			}
			
			$priority=new ErrorPriorityENUM($this->_bugData['PriorityLevel']);
			$this->_smarty->assign(
				"PRIORITY_LEVEL",
				array(
					'values' => $priority->getNormalized(),
					'selected' => $priority->getValue()
				)
			);
			
			$this->_smarty->assign("USER_ID",$this->_userData["USER_ID"]);
			$addCommentError=$this->_controller->error->getErrorByName("addCommentError");
			if ($addCommentError!=null)
			{
				$exception=$addCommentError["error"];
				$this->_smarty->assign("ERROR",$exception->getMessage());
				$this->_smarty->assign("DATA",$addCommentError["postData"]);
			}
			
			$editItemError=$this->_controller->error->getErrorByName("editBugError");
			if ($editItemError!=null)
			{
				$exception=$editItemError["error"];
				$this->_smarty->assign("ERROR",$exception->getMessage());
			}
		}
	}
	
	protected function deleteSelectedItems()
	{
		$checkboxes=$this->request->getPost("del_i");
		$this->_commentsModel->deleteCommentsFromList(
			$this->_userData["USER_ID"],
			$checkboxes
		);
        $this->navigate($this->_controller->_url.'#comments'); 
	}
	
	/**
	*
	* Called when changed item data
	*/
	private function editState()
	{
        $postData=$this->request->getPost();
		$stateEnum=new ErrorStatusENUM($postData['state']);
		$itemsFacade=new ItemsFacade(
			$this->_bugsOperation, 
			$this->_history, 
			$this->_controller->auth,
			$this->_bugData['ProjectID']
		);
		try 
		{
			$editResult=$itemsFacade->editItem(
				$this->_bugData['ID'], 
				$stateEnum, 
				array(
					'Title' => $postData['title'],
					'PriorityLevel' => $postData['priority'],
					'DefectType' => $postData['error_type'],
					'Description' => $postData['descr'],
					'StepsText' => $postData['steps'],
					'AssignedTo' => $postData['assigned_to'],
					'HoursRequired' => $postData['hour_req'],
					'AddHours' => $postData['add_hour_fact']
				)
			);
		}
		catch(Exception $exception)
		{
			$error = array(
				"error" => $exception,
				"postData" => $postData
			);
			$this->_controller->error->addError("editBugError",$error);
		}
		if ($editResult) 
		{	
			$this->_bugData["Status"]=$stateEnum->getValue();
			$this->_bugData["Title"]=$postData['title'];
			$this->_bugData["PriorityLevel"]=$postData['priority'];
			$this->_bugData["DefectType"]=$postData['error_type'];
			$this->_bugData["Description"]=$postData['descr'];
			$this->_bugData["StepsText"]=$postData['steps'];
			$this->_bugData["AssignedTo"]=$postData['assigned_to'];
			$this->_bugData["HoursRequired"]=$postData['hour_req'];
			$this->_bugData["HoursFact"]+=$postData['add_hour_fact'];
			$this->_controller->error->addError("editBugErrorOK",true);
		}
	}
    
    protected function addComment()
    {
        $postData=$this->request->getParams();
        try
        {
            $this->_commentsModel->setReportComment(
                $this->_bugData['ProjectID'], 
                $this->_userData["USER_ID"], 
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
        $this->navigate($this->_controller->_url.'#comments');
    }
}
?>