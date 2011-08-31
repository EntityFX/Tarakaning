<?php
require_once 'engine/modules/Auth/Logic/UserAuth.php';

	class AuthCheckerControllerAbstract extends ModuleController
	{
		const LOGIN_URL='/login/';
		const MY_PROJECTS_URL='/my/projects/';
		
		public $error;
		
		private $_moduleUrl;
		
		/**
		 * 
		 * Объект аутентификации
		 * @var UserAuth
		 */
		public $auth;
		
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
			$this->auth=new UserAuth();
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
				parent::initializePages();
			}

		}
	}