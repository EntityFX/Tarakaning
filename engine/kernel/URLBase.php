<?php
set_include_path(ZEND_FOLDER);
require_once 'Zend/Session.php';	
require_once 'Zend/Controller/Request/Http.php';
require_once 'Zend/Controller/Response/Http.php';
	
	/**
	 * 
	 * Содержит информацию о модуле
	 * @author Artem Solopiy
	 *
	 */
	abstract class URLBase
	{
	    /**
	     * 
	     * ID модуля
	     * @var int
	     */
		protected $_moduleID;
        /**
         * 
         * URL
         * @var string
         */
        protected $_url;
        /**
         * 
         * URL разбитый на разделы
         * @var array
         */
        protected $_urlArray;
        /**
         * 
         * Проверка, использует параметры
         * @var bool
         */
        protected $_useParameters;
        /**
         * 
         * Массив параметров
         * @var array
         */
        protected $_parameters;
        /**
         * 
         * Имя модуля
         * @var string
         */
        protected $_moduleName;
        /**
         * 
         * Описание модуля
         * @var string
         */
        protected $_moduleDescription;
        /**
         * 
         * ID родительского раздела
         * @var int
         */
        protected $_parentID;
        /**
         * 
         * ID раздела
         * @var int
         */
        protected $_sectionID; 
		/**
		 * 
		 * Заголовок раздела
		 * @var string
		 */
        protected $_title;
        /**
         * 
         * Тип модуля
         * @var unknown_type
         */
        protected $_moduleType;
        /**
         * 
         * Объект запроса
         * @var Zend_Controller_Response_Http
         */
		public $response;
		/**
		 * 
		 * Объект ответа
		 * @var Zend_Controller_Request_Http
		 */
		public $request;
		/**
		 * 
		 * Данные о модуле
		 * @var array
		 */
        private $_initData;
        
        /**
         * 
         * Конструктор
         * @param array $initData ссылка на данные модуля
         */
        public function __construct(&$initData)
        {
        	$this->_sectionID=(int)$initData["id"];             
            $this->_parameters=$initData["parameters"];
            $this->_url=$initData["url"];
            $this->_urlArray=$initData["urlArray"];
            $this->_useParameters=(boolean)$initData["isParameters"];
            $this->_moduleName=$initData["name"]; 
            $this->_moduleDescription=$initData["descr"]; 
            $this->_moduleType=$initData["type"]; 
            $this->_parentID=(int)$initData["pid"]; 
            $this->_moduleID=(int)$initData["moduleID"];
            $this->_title=$initData["title"];
            $this->_initData=$initData;
			$this->response=new Zend_Controller_Response_Http();
			$this->request=new Zend_Controller_Request_Http();
			Zend_Session::start();
        }
		
        /**
         * 
         * Получить данные о модуле
         * @return array
         */
        public function getInitData()
        {
        	return $this->_initData;
        }
        
        /**
         * 
         * Получить URL без параметров
         * @return string
         */
        public function getModuleURL()
        {
        	$count=count($this->_urlArray);
        	$paramCount=count($this->_parameters);
        	$urlBaseCount=$count-$paramCount-1;
			for($it=0;$it<=$urlBaseCount;$it++)
			{
				$res.=$this->_urlArray[$it];
				if ($it!=0)
				{
					$res.="/";
				}
			}
			return $res;
        }
        
        /**
         * 
         * Перенаправить на другой адрес
         * @param string $url Адрес перенаправки
         */
		public function navigate($url)
		{
			$url=(string)$url;
			if ($url=="") $url="/";
			$this->response->setRedirect($url);
		}
		
		public function setCookie(Zend_Http_Cookie $cookie)
		{
			$str=$cookie->getName()."=".$cookie->getValue()."; ";
			$str.="expires="."Fri, 31-Dec-2019 23:59:59 GMT; ";
			$str.="path=".$cookie->getPath()."; ";
			$str.="domain=".$cookie->getDomain();
			$this->response->setHeader("Set-Cookie", $str);
		}
		
		/**
		 * 
		 * Деструктор
		 */
		public function __destruct()
		{
			if (!$this->response->isRedirect())
			{
				$this->response->sendResponse();
			}
			else
			{
				$this->response->sendHeaders();
			}
		}
		
		static public function getGlobalEncoding()
		{
			return FrontController::getGlobalEncoding();
		}
	}