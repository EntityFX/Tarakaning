<?php
require_once 'engine/kernel/HTMLPage.php';
set_include_path($_SERVER["DOCUMENT_ROOT"]."/engine/system/zend_search/");
require_once 'Zend/Captcha/Image.php';
require_once 'Zend/Session.php';	
	class RegistrationPage extends HTMLPage
	{
		protected function onInit()
		{
			/*$captcha=new Zend_Captcha_Image();
			var_dump($_SESSION);
			$namespace=new Zend_Session_Namespace();
			$captcha->setSession($namespace);
			$captcha->generate();
			var_dump($captcha->render());*/
		}
		
		protected function doAssign()
		{
			$registrationError=$this->_controller->error->getErrorByName("registrationError");
			if ($registrationError!=null)
			{
				$this->_smarty->assign("ERROR",$registrationError["error"]->getMessage());
				$this->_smarty->assign("DATA",$registrationError["postData"]);
			}
		}
	}
?>