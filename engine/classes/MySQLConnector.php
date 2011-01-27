<?php
/**
* ���� � ������� MySQLConnector.
* @package MySQL  
* @author Solopiy Artem
* @version 0.9 Beta
* @copyright Idel Media Group: Developers Team (Solopiy Artem, Jusupziyanov Timur) � 2010 
*/
    
    require_once "MySQL.php";
    
    
    /**
    * ������ ����� � ��������� � ��
    * @abstract
    */
    abstract class MySQLConnector
    {

        /**
        * ������ MySQL
        * 
        * @var MySQL
        */
        protected $_sql;
        
        /**
        * ����������� �������������� ������ $_sql
        * 
        * 
        */
        public function __construct()
        {
            $this->_sql=MySQL::creator(DB_SERVER,DB_USER,DB_PASSWORD);
            $this->_sql->selectDB(DB_NAME);
        }
        
        /**
        * ���������� ���������� ����� ��� ������� �������� �������
        * 
        */
        public function __destruct()
        {
            $this->_sql->debugging=false;
        }
    }
?>
