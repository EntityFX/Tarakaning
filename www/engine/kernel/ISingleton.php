<?php
    /**
    * ������������ ��� ���������� ���������
    */
    interface ISingleton
    {
        /**
        * ���������� ��������� ���������
        * 
        */
        static function &getInstance();
    }
?>
