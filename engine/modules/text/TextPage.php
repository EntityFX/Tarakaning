<?php
	class TextPage extends PageBase
	{
		private $_txtModule;
		
		protected function onInit()
		{
			$this->_txtModule=new TextModule($this->_sectionID);	
		}
		
		protected function doAssign()
		{
			$textData=$this->_txtModule->getTextData();
			$this->_smarty->assign("info",$textData["data"]);
			$this->_smarty->assign("titleTag",$this->_title);			
		}
	}