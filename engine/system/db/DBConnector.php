<?php
/**
* Файл с классом MySQLConnector.
* @package MySQL  
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Developers Team (Solopiy Artem, Jusupziyanov Timur) © 2010 
*/

    require_once SOURCE_PATH."engine/config/databaseConsts.php";
    
    require_once SOURCE_PATH."engine/kernel/ISingleton.php";    
    
    require_once SOURCE_PATH."engine/system/db/mysql/MySQL.php";
    
    
    /**
    * Создаёт класс и соединяет с БД
    * @abstract
    */
    class DBConnector implements ISingleton
    {

        /**
        * Объект MySQL
        * 
        * @var MySQL
        */
        protected $_sql;
        
        static private $_instance; 
        
        /**
        * Конструктор инициализирует объект $_sql
        * 
        */
        protected function __construct()
        {
            $this->_sql=MySQL::getInstance(DB_SERVER,DB_USER,DB_PASSWORD);
            MySQL::$globalDebugging=SQL_DEBUG;
            $this->_sql->selectDB(DB_NAME);
        }
        
        /**
        * Деструктор сбрасывает режим для отладки текущего объекта
        * 
        */
        public function __destruct()
        {
            $this->_sql->debugging=false;
            $this->_sql->clearLimit();
        }
        
        /**
        * Использовать лимит
        * 
        * @param Integer $from Начальное положение
        * @param Integer $size Размер лимита
        */
        protected function useLimit($from,$size=0)
        {
            if (!$this->_sql->isLimit() && $size>0)
            {
                $this->_sql->setLimit($from,$size);
            } 
            else
            {
                $this->_sql->clearLimit();
            }   
        }

        /**
        * Использовать сортировку
        * 
        * @param AEnum $field Сортируемое поле
        * @param MySQLOrderEnum $direction Направление
        */
        protected function useOrder(AEnum $field, MySQLOrderEnum $direction)
        {
            if (!$this->_sql->isOrdered())    
            {     
                $this->_sql->setOrder($field,$direction);
            }
            else
            {
                $this->_sql->clearOrder();
            }
        }
        
        public static function &getInstance()
        {
        	if (self::$_instance==null)
        	{
        		self::$_instance=new DBConnector();
        	}	
        	return self::$_instance;
        }
        
        public function getDB()
        {
        	return $this->_sql;
        }
    }
?>
