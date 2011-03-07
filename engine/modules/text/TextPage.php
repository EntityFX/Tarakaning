<?php
	class TextPage extends PageBase
	{
		protected function doAssign()
		{
			$this->_smarty->assign("info",1);			
		}
	}