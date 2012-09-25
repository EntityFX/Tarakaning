<?php

require_once SOURCE_PATH.'engine/kernel/HTMLPageController.php';

class ErrorPage extends HTMLPageController
{
	protected function doAssign()
	{
		$this->_smarty->assign("ErrorURL",$this->_url);
		$this->_smarty->assign("BaseURL",
		$this->request->getScheme().
            	"://".$this->request->getHttpHost().
		$this->request->getRequestUri()
		);
	}
	
	protected function onInit()
	{
		
	}

	public function __destruct()
	{
		$this->response->setHttpResponseCode(404);
		$this->response->sendHeaders();
		parent::__destruct();
	}
}