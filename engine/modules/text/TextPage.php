<?php
	class TextPage extends PageBase
	{
		/**
		 * 
		 * Enter description here ...
		 * @var TextModule
		 */
		private $_txtModule;
		
		protected function onInit()
		{
			$this->_txtModule=new TextModule();	
		}
		
		protected function doAssign()
		{
			$textData=$this->_txtModule->getTextData($this->_sectionID);
			$this->_smarty->assign("info",$textData["data"]);
			$this->_smarty->assign("titleTag",$this->_title);			
		}
	}