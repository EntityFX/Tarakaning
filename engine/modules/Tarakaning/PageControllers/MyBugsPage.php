<?php
require_once 'InfoBasePage.php';
require_once 'engine/modules/Tarakaning/Logic/ErrorReportsController.php';

class MyBugsPage extends InfoBasePage 
{
	private $_bugsData;
	
	protected function onInit()
	{
		parent::onInit();
		$bugsController=new ErrorReportsController();
		//$userData=$this->_controller->auth->getName();
		$this->_bugsData = $bugsController->getMyOrdered(new ErrorFieldsENUM(ErrorFieldsENUM::TIME) , 
			new MySQLOrderENUM(MySQLOrderENUM::ASC), $from, $size);
	}
	
	protected function doAssign()
	{
		parent::doAssign();
	}
}
?>