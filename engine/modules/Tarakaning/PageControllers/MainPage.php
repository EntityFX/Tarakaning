<?php
require_once SOURCE_PATH.'engine/kernel/SinglePage.php';

class MainPage extends SinglePage
{

	
	protected function onInit()
	{
		$this->navigate(TarakaningController::MY_PROJECTS_URL);
	}

}
?>