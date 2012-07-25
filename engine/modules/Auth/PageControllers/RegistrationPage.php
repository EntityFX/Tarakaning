<?php
require_once SOURCE_PATH.'engine/kernel/HTMLPageController.php';
require_once 'Zend/Captcha/Image.php';

	class RegistrationPage extends HTMLPageController
	{
		private $_captchaImage;
		
		private $_captchaID;
        
        public static $_fontPath = 'fonts/arial.ttf';
        
        public static $_imgCaptchaUrl = '/img/captcha/';
		
		protected function onInit()
		{
            $captcha=new Zend_Captcha_Image();
			$captcha->setFont(self::$_fontPath)
                    ->setImgDir(SOURCE_PATH.self::$_imgCaptchaUrl)
                    ->setImgUrl(self::$_imgCaptchaUrl)
					->setName('registrationCaptcha')
					->setWidth(150)
					->setHeight(54)
                    ->setLineNoiseLevel(10)
                    ->setDotNoiseLevel(10)
					->setFontSize(24)
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