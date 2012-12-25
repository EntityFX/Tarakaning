<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AddItemForm
 *
 * @property array $projectsList 
 * @property-read array $itemsTypeList 
 * @property-read array $defectTypeList
 * @property-read array $priorityList
 * @property-read array $projectUsersList 
 * @author Artem
 */
class AddItemForm extends CFormModel {
    public $projectId;
    
    public $title;
    
    public $itemKind;
    
    public $defectType;
    
    public $priority;
    
    public $assigned;
    
    public $hoursRequired = 0;
    
    public $itemDescription;
    
    public $steps;
    
    private $_projectsList = array();
    
    public function getProjectsList() {
        return $this->_projectsList;
    }
    
    public function setProjectsList(array $value) {
        $this->_projectsList = $value;
    }
    
    public function getItemsTypeList() {
        return array(
            ItemDBKindENUM::TASK => Yii::t('global', '* Задача'),
            ItemDBKindENUM::DEFECT => Yii::t('global', '* Дефект')
        );
    }
    
    public function getDefectTypeList() {
        return array(
            ItemTypeENUM::CRASH => Yii::t('global', '* Крах'),
            ItemTypeENUM::COSMETIC => Yii::t('global', '* Ошибка в интерфейсе'),
            ItemTypeENUM::ERROR_HANDLE => Yii::t('global', '* Исключение'),
            ItemTypeENUM::FUNCTIONAL => Yii::t('global', '* Функциональная'),
            ItemTypeENUM::MINOR => Yii::t('global', '* Незначительнаый'),
            ItemTypeENUM::MAJOR => Yii::t('global', '* Важный'),
            ItemTypeENUM::SETUP => Yii::t('global', '* Ошибка установки'),
            ItemTypeENUM::BLOCK => Yii::t('global', '* Блокирующая ошибка')
        );
    }
    
    public function getPriorityList() {
        return array(
            ItemPriorityENUM::MINIMAL => Yii::t('global', '* Низкий'),
            ItemPriorityENUM::NORMAL => Yii::t('global', '* Обычный'),
            ItemPriorityENUM::HIGH => Yii::t('global', '* Важный')
        );
    }
    
    public function getProjectUsersList() {
        return array();
    }

}

?>
