<?php

	require_once 'SinglePage.php';
	
	require_once 'engine/system/zend/JSON.php';

	abstract class JSONPage extends SinglePage
	{
		
		protected $_jsonOutput;
		
		/**
		 * 
		 * Конструктор контроллера страницы
		 * @param PageController $pageController
		 */
		public function __construct(ModuleController $pageController)
		{
			parent::__construct($pageController);
			$this->response->setHeader("Content-Type", "application/json; charset=utf-8");
		}
		
		public function __destruct()
		{
			if (!$this->response->isRedirect())
			{
				$this->doAssign();
				$this->response->sendHeaders();
				echo Zend_Json::encode($this->utf8Encode($this->_jsonOutput),true);
			}
			else
			{
				$this->response->sendHeaders();
			}
		}
		
		private function utf8Encode($res)
		{
			if (is_array($res))
			{
				foreach($res as $key => $element)
				{
					$out[$key]=$this->utf8Encode($element);
				}
				return $out;
			}
			else if (is_string($res))
			{
				return iconv("cp1251", "UTF-8",$res);
			}
			else if (is_object($res))
			{
				$ref=new ReflectionClass($res);
				$prop=$ref->getProperties(ReflectionProperty::IS_PUBLIC);
				foreach ($prop as $val)
				{
					$val->setValue($res,$this->utf8Encode($val->getValue($res)));
				}
				return $res;
			}
			else
			{
				return $res;
			}
		}
		
		abstract protected function doAssign();
	}