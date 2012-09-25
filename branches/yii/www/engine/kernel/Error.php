<?php

	require_once 'ISingleton.php';
	/**
	 * 
	 * �����, ����������� � �������� ������
	 * @author Artem Solopiy
	 * 
	 */
	class Error implements ISingleton
	{
        static private $_instance;
        
        /**
         * 
         * ����������� ������� - ����� ��������
         */
        protected  function __construct()
        {
        	session_start();
        }
		
        /**
         * 
         * �������� ������
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
         *�������� ��� ������������ ������
         */
       	public function getErrors()
       	{
       		$errors=$_SESSION["ERROR"];
       		unset($_SESSION["ERROR"]);
       		return $errors;	
       	}
       	
       	/**
       	 * 
       	 * �������� ������ �� �����
       	 * @param $name string ��� ������
       	 */
	    public function getErrorByName($name)
       	{
			$error=$_SESSION["ERROR"][$name];
       		unset($_SESSION["ERROR"][$name]);
       		return $error;	
       	}
       	
       	/**
       	 * 
       	 * �������� ������ � ����� 
       	 * @param $name string ��� ������
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
       	 * �������� �� ������������� ������ � ����� ������
       	 * @param $name string ��� ������
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