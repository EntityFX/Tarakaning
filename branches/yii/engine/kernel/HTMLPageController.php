<?php

	require_once 'SinglePageController.php';
	
	require_once 'SmartyExst.php';

	abstract class HTMLPageController extends SinglePageController
	{
		
		/**
		 *	
		 * @var SmartyExst	
		 */
		protected $_smarty;
				
		protected $_templatePath="";
		
		protected $_controller;
		
		/**
		 * 
		 * Конструктор контроллера страницы
		 * @param PageController $pageController
		 */
		public function __construct(ModuleController $pageController, $templatePath="")
		{
			parent::__construct($pageController);
			$this->_smarty=new SmartyExst();
			$this->_templatePath=$templatePath;
		}
		
		public function __destruct()
		{
			if (!$this->response->isRedirect())
			{
				$this->doAssign();
				if ($this->_templatePath!="")
				{
					if ($this->_smarty->templateExists($this->_templatePath))
					{
						$this->response->setBody($this->_smarty->fetch($this->_templatePath),null);
						$this->response->sendResponse();
					}
					else 
					{
						var_dump($this->_smarty->template_dir[0].'/'.$this->_templatePath);
						throw new Exception("Неверный путь к TPL: ".$this->_smarty->template_dir[0]."/$this->_templatePath ");					
					}	
				}
			}
			else
			{
				$this->response->sendHeaders();
			}
		}
		
		abstract protected function doAssign();
	}