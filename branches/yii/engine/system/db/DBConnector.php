<?php
/**
* ���� � ������� MySQLConnector.
* @package MySQL  
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Developers Team (Solopiy Artem, Jusupziyanov Timur) � 2010 
*/

    require_once SOURCE_PATH."engine/config/databaseConsts.php";
    
    require_once SOURCE_PATH."engine/kernel/ISingleton.php";    
    
    require_once SOURCE_PATH."engine/system/db/mysql/MySQL.php";
    
    
    /**
    * ������ ����� � ��������� � ��
    * @abstract
    */
    class DBConnector implements ISingleton
    {

        /**
        * ������ MySQL
        * 
        * @var MySQL
        */
        protected $_sql;
        
        static private $_instance; 
        
        /**
        * ����������� �������������� ������ $_sql
        * 
        */
        protected function __construct()
        {
            $this->_sql=MySQL::getInstance(DB_SERVER,DB_USER,DB_PASSWORD);
            MySQL::$globalDebugging=SQL_DEBUG;
            $this->_sql->selectDB(DB_NAME);
        }
        
        /**
        * ���������� ���������� ����� ��� ������� �������� �������
        * 
        */
        public function __destruct()
        {
            $this->_sql->debugging=false;
            $this->_sql->clearLimit();
        }
        
        /**
        * ������������ �����
        * 
        * @param Integer $from ��������� ���������
        * @param Integer $size ������ ������
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
        * ������������ ����������
        * 
        * @param AEnum $field ����������� ����
        * @param MySQLOrderEnum $direction �����������
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
