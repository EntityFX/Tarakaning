<?php
set_include_path(ZEND_FOLDER);
require_once 'Zend/Session.php';	
require_once 'Zend/Controller/Request/Http.php';
require_once 'Zend/Controller/Response/Http.php';
	
	/**
	 * 
	 * �������� ���������� � ������
	 * @author Artem Solopiy
	 *
	 */
	abstract class URLBase
	{
	    /**
	     * 
	     * ID ������
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
         * URL �������� �� �������
         * @var array
         */
        protected $_urlArray;
        /**
         * 
         * ��������, ���������� ���������
         * @var bool
         */
        protected $_useParameters;
        /**
         * 
         * ������ ����������
         * @var array
         */
        protected $_parameters;
        /**
         * 
         * ��� ������
         * @var string
         */
        protected $_moduleName;
        /**
         * 
         * �������� ������
         * @var string
         */
        protected $_moduleDescription;
        /**
         * 
         * ID ������������� �������
         * @var int
         */
        protected $_parentID;
        /**
         * 
         * ID �������
         * @var int
         */
        protected $_sectionID; 
		/**
		 * 
		 * ��������� �������
		 * @var string
		 */
        protected $_title;
        /**
         * 
         * ��� ������
         * @var unknown_type
         */
        protected $_moduleType;
        /**
         * 
         * ������ �������
         * @var Zend_Controller_Response_Http
         */
		public $response;
		/**
		 * 
		 * ������ ������
		 * @var Zend_Controller_Request_Http
		 */
		public $request;
		/**
		 * 
		 * ������ � ������
		 * @var array
		 */
        private $_initData;
        
        /**
         * 
         * �����������
         * @param array $initData ������ �� ������ ������
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
         * �������� ������ � ������
         * @return array
         */
        public function getInitData()
        {
        	return $this->_initData;
        }
        
        /**
         * 
         * �������� URL ��� ����������
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
         * ������������� �� ������ �����
         * @param string $url ����� ������������
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
		 * ����������
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