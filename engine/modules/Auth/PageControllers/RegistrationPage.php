<?php
require_once 'engine/kernel/HTMLPage.php';
require_once 'Zend/Captcha/Image.php';

	class RegistrationPage extends HTMLPage
	{
		private $_captchaImage;
		
		private $_captchaID;
		
		protected function onInit()
		{
			$captcha=new Zend_Captcha_Image();
			$captcha->setFont('fonts/calibri.ttf')
					->setName('registrationCaptcha')
					->setWidth(200)
					->setHeight(50)
					->setFontSize(36)
					->setWordLen(6)
					->generate();
			$this->_captchaImage=$captcha->render();
			
			$this->_captchaID=$captcha->getId();
		}
		
		protected function doAssign()
		{
			$this->_smarty->assign('CAPTCHA',$this->_captchaImage);
			$this->_smarty->assign('CAPTCHA_ID',$this->_captchaID);
			$registrationError=$this->_controller->error->getErrorByName('registrationError');
			if ($registrationError!=null)
			{
				$this->_smarty->assign('ERROR',$registrationError['error']->getMessage());
				$this->_smarty->assign('DATA',$registrationError['postData']);
			}
		}
	}
?>