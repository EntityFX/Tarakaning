<?php

	require_once 'ISingleton.php';
	/**
	 * 
	 * Класс, оперирующий с ошибками логики
	 * @author Artem Solopiy
	 * 
	 */
	class Error implements ISingleton
	{
        static private $_instance;
        
        /**
         * 
         * Конструктор скрытый - класс синглтон
         */
        protected  function __construct()
        {
        	session_start();
        }
		
        /**
         * 
         * Получить объект
         */
		static public function &getInstance()
        {
			if (self::$_instance==null)
			{
				self::$_instance=new Error(); 	
			} 
			return self::$_instance;       	
        }
		
        /**
         * 
         *Получить все непоказанные ошибки
         */
       	public function getErrors()
       	{
       		$errors=$_SESSION["ERROR"];
       		unset($_SESSION["ERROR"]);
       		return $errors;	
       	}
       	
       	/**
       	 * 
       	 * Получить ошибку по имени
       	 * @param $name string Имя ошибки
       	 */
	    public function getErrorByName($name)
       	{
			$error=$_SESSION["ERROR"][$name];
       		unset($_SESSION["ERROR"][$name]);
       		return $error;	
       	}
       	
       	/**
       	 * 
       	 * Добавить ошибку в набор 
       	 * @param $name string Имя ошибки
       	 * @param $error Exception | array
       	 */
       	public function addError($name,$error)
       	{
       		$name=(string)$name;
       		if (!$this->isExsist($name))
       		{
       			$_SESSION["ERROR"][$name]=$error;
       		}
       	}
       	
       	/**
       	 * 
       	 * Проверка на существование ошибки с таким именем
       	 * @param $name string Имя ошибки
       	 */
       	private function isExsist($name)
       	{
       		if ($_SESSION["ERROR"]!=null)
       		{
       			return array_key_exists($name, $_SESSION["ERROR"]);		
       		}
       		else
       		{
       			return null;
       		}	
       	}

	}