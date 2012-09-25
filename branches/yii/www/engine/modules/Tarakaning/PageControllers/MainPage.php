<?php
require_once SOURCE_PATH.'engine/kernel/SinglePageController.php';

class MainPage extends SinglePageController
{

	
	protected function onInit()
	{
		$this->navigate(TarakaningController::MY_PROJECTS_URL);
	}

}
?>