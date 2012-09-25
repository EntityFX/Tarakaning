<?php
    /**
    * Используется для реализации синглтона
    */
    interface ISingleton
    {
        /**
        * Возвращает инстанцию синглтона
        * 
        */
        static function &getInstance();
    }
?>
