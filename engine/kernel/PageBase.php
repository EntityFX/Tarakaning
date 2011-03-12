<?php

	require_once "engine/system/zend/Controller/Request/Http.php";
	
	require_once "engine/system/zend/Controller/Response/Http.php";

	require_once 'URLBase.php';
	
	require_once 'SmartyExst.php';

	abstract class PageBase extends URLBase
	{
		
		protected $_smarty;
				
		public $request;

		public $response;
		
		protected $_templatePath="";
		
		/**
		 * 
		 * Конструктор контроллера страницы
		 * @param PageController $pageController
		 */
		public function __construct(PageController $pageController, $templatePath="")
		{
			parent::__construct($pageController->getInitData());
			$this->request=new Zend_Controller_Request_Http();
			$this->response=new Zend_Controller_Response_Http();
			$this->_smarty=new SmartyExst();
			$this->_templatePath=$templatePath;
			$this->onInit();
		}
		
		public function __destruct()
		{
			$this->doAssign();
			if ($this->_templatePath!="")
			{
				if ($this->_smarty->template_exists($this->_templatePath))
				{
					echo $this->_smarty->fetch($this->_templatePath);
				}
				else 
				{
					throw new Exception("Неверный путь к TPL: ".$this->_smarty->template_dir."/$this->_templatePath ");					
				}	
			}
		}
		
		protected function navigare($url)
		{
			$url=(string)$url;
			if ($url=="") $url="/";
			$this->response->setRedirect($url);
		}
		
		abstract protected function doAssign();
		
		abstract protected function onInit();
	}