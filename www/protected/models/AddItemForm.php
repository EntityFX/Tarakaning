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
 * @property-read string $itemKindText
 * @property-read string $priorityText
 * @property-read string $defectTypeText
 * @property-read array $statusList
 * @property-read string $statusText
 * @author Artem
 */
class AddItemForm extends CFormModel {
    public $itemId;
    
    public $projectId;
    
    public $projectName;
    
    public $ownerId;
    
    public $nickName;
    
    public $title;
    
    public $itemKind = ItemDBKindENUM::TASK;
    
    public $defectType;
    
    public $priority = ItemPriorityENUM::NORMAL;
    
    public $status;
    
    public $createDateTime;
    
    public $assigned;
    
    public $assignedNickName;
    
    public $hoursRequired = 0;
    
    public $hoursFact = 0;
    
    public $itemDescription;
    
    public $steps;
    
    private $_projectsList = array();
    
    public function rules() {
        $itemKindList = new ItemDBKindENUM();
        $defectTypeList = new ItemDBKindENUM();
        $priorityList = new ItemPriorityENUM();
        return array(
            array('title', 'required'),
            array('itemKind', 'safe'),
            array('defectType', 'safe'),
            array('priority', 'safe'),
            array('assigned', 'safe'),
            array('itemKind', 'in', 'range' => $itemKindList->getArray()),
            array('priority', 'in', 'range' => $priorityList->getArray()),
            array('itemDescription', 'safe'),
            array('steps', 'safe'),
            array('hoursRequired', 'numerical', 'integerOnly' => true, 'min' => 0, 'max' => 65535)
        );
    }
    
    public function getProjectsList() {
        return $this->_projectsList;
    }
    
    public function setProjectsList(array $value) {
        $this->_projectsList = $value;
    }
    
    public function getItemsTypeList() {
        return ItemDataHelper::getItemsTypeList();
    }
    
    public function getDefectTypeList() {
        return ItemDataHelper::getDefectTypeList();
    }
    
    public function getPriorityList() {
        return ItemDataHelper::getPriorityList();
    }
    
    public function getStatusList() {
        return ItemDataHelper::getStatusList();
    }
    
    public function getProjectUsersList() {
        return array();
    }
    
    /**
     * 
     * @param array $itemServiceData
     * @return AddItemForm
     */
    public static function createByItemServiceData(array $itemServiceData) {
        $model = new AddItemForm();
        $model->itemId = $itemServiceData[ItemFullInfoView::ITEM_ID_FIELD];
        $model->itemKind = $itemServiceData[ItemFullInfoView::KIND_FIELD]; 
        $model->ownerId = $itemServiceData[ItemFullInfoView::USER_ID_FIELD]; 
        $model->assigned = $itemServiceData[ItemFullInfoView::ASSIGNED_TO_FIELD];
        $model->hoursRequired = $itemServiceData[ItemFullInfoView::HOUR_REQUIRED_FIELD];
        $model->hoursFact = $itemServiceData[ItemFullInfoView::HOUR_FACT_FIELD];
        $model->projectId = $itemServiceData[ItemFullInfoView::PROJECT_ID_FIELD];
        $model->priority = $itemServiceData[ItemFullInfoView::PRIORITY_LEVEL_FIELD]; 
        $model->status = $itemServiceData[ItemFullInfoView::STATUS_FIELD]; 
        $model->createDateTime = $itemServiceData[ItemFullInfoView::CRT_TM_FIELD]; 
        $model->title = $itemServiceData[ItemFullInfoView::TITLE_FIELD]; 
        $model->defectType = $itemServiceData[ItemFullInfoView::ERROR_TYPE_FIELD]; 
        $model->itemDescription = $itemServiceData[ItemFullInfoView::DESCRIPTION_FIELD]; 
        $model->steps = $itemServiceData[ItemFullInfoView::STEPS_TEXT_FIELD]; 
        $model->createDateTime = $itemServiceData[ItemFullInfoView::CRT_TM_FIELD]; 
        $model->projectName = $itemServiceData[ItemFullInfoView::PROJECT_NAME_FIELD];
        $model->nickName = $itemServiceData[ItemFullInfoView::NICK_NAME_FIELD];
        $model->projectName = $itemServiceData[ItemFullInfoView::PROJECT_NAME_FIELD];
        $model->assignedNickName = $itemServiceData[ItemFullInfoView::ASSIGNED_NICK_NAME_FIELD];
        return $model;
    }
    
    public function getPriorityText() {
        $list = $this->getPriorityList();
        return $list[$this->priority];
    }
    
    public function getItemKindText() {
        $list = $this->getItemsTypeList();
        return $list[$this->itemKind];
    }
    
    public function getDefectTypeText() {
        $list = $this->getDefectTypeList();
        return $list[$this->defectType];
    }
    
    public function getStatusText() {
        $list = $this->getStatusList();
        return $list[$this->status];
    }
    
    public function getCreateDateTimeText() {
        return YII::app()->dateFormatter->formatDateTime($this->createDateTime, 'long', 'medium');
    }
}

?>
