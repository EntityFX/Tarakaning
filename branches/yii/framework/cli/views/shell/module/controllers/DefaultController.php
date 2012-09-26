<?php

class DefaultController extends ControllerBase
{
	public function actionIndex()
	{
		$this->render('index');
	}
}