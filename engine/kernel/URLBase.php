<?php
	abstract class URLBase
	{
	    protected $_moduleID;
        
        protected $_url;
        
        protected $_urlArray;
        
        protected $_useParameters;
        
        protected $_parameters;
        
        protected $_moduleName;
        
        protected $_moduleDescription;
        
        protected $_parentID;
        
        protected $_sectionID; 

        protected $_title;

        private $_initData;
        
        public function __construct(&$initData)
        {
        	$this->_sectionID=(int)$initData["id"];             
            $this->_parameters=$initData["parameters"];
            $this->_url=$initData["url"];
            $this->_urlArray=$initData["urlArray"];
            $this->_useParameters=(boolean)$initData["isParameters"];
            $this->_moduleName=$initData["name"]; 
            $this->_moduleDescription=$initData["descr"]; 
            $this->_parentID=(int)$initData["pid"]; 
            $this->_moduleID=(int)$initData["moduleID"];
            $this->_title=$initData["title"];
            $this->_initData=$initData;
        }

        public function getInitData()
        {
        	return $this->_initData;
        }
		
	}