<?php

	require_once 'URLBase.php';
	
	require_once 'SmartyExst.php';

	abstract class SinglePage extends URLBase
	{
		
		/**
		 * 
		 * Module controller
		 * @var ModuleController
		 */
		protected $_controller;
		
		/**
		 * 
		 * Конструктор контроллера страницы
		 * @param PageController $pageController
		 */
		public function __construct(ModuleController $pageController)
		{
			parent::__construct($pageController->getInitData());
			$this->_controller=$pageController;
			$this->onInit();
		}
		
		abstract protected function onInit();
	}