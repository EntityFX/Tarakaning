<?php
require_once SOURCE_PATH.'engine/modules/Tarakaning/Logic/ConcreteUser.php';

	class AuthCheckerControllerAbstract extends ModuleController
	{
		const LOGIN_URL='/login/';
		const MY_PROJECTS_URL='/my/projects/';
		
		public $error;
		
		private $_moduleUrl;
		
		/**
		 * 
		 * ������ ��������������
		 * @var UserAuth
		 */
		public $auth;
		
		public $menu=array(
			array("title" => "��� �������", "url" => "/my/projects/", "id" => 62),
			array("title" => "������ �������", "url" => "/my/project/bugs/", "id" => 87),
			array("title" => "��� ������", "url" => "/my/bugs/", "id" => 63),
			array("title" => "��� ������", "url" => "/requests/", "id" => 79),
			array("title" => "�����", "url" => "/search/", "id" => 76)
		);
		
		private function normalizeMenu()
		{
			foreach ($this->menu as $key => $value)
			{
				if ($value["id"]==$this->_sectionID)
				{
					$this->menu[$key]["cur"]=true;
					break;
				}
			}
		}
		
		public function checkLogIn(&$allowedPages,$loginURL)
		{
   			if (array_search($this->_moduleUrl, $allowedPages)!==false)
   			{
   				return true;
   			}
   			else if (!$this->auth->isEntered())
   			{
   				$this->navigate($loginURL);
   				return false;
   			}
   			else
   			{
   				return true;
   			}
		}
		
		public function initializePages()
		{
			$this->error=Error::getInstance();
			UserAuth::$authTableName="Users";
			$this->auth=new ConcreteUser();
			$this->_moduleUrl=$this->getModuleURL();
			$allowedPages=array(
	   			"/login/",
				"/login/do/",
				"/logout/",
				"/registration/",
				"/registration/do/"
	   		);
			if ($this->checkLogIn(&$allowedPages,"/login/"))
			{
				$this->normalizeMenu();
				parent::initializePages();
			}
		}
	}