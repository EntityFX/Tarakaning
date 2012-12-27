<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ItemController
 *
 * @author EntityFX
 */
class ItemController extends ContentControllerBase {
    
    public function filters()
    {
        return array(
            'accessControl',
        );
    }
    
    public function actionAdd() {
        $model = new AddItemForm();
        
        $formData = $this->request->getPost("AddItemForm");
        if (isset($formData)) {
            $model->attributes = $formData;
            if ($model->validate()) {
                try
                {
                    $itemSevice = $this->ioc->create('IItemService');
                    
                    $itemId = $itemSevice->addReport(
                        new ItemDBKindENUM($model->itemKind), 
                        new ItemPriorityENUM($model->priority), 
                        new ItemTypeENUM($model->defectType), 
                        $model->title, 
                        $model->hoursRequired, 
                        $model->itemDescription, 
                        $model->steps,
                        $model->assigned
                    );
                    
                    $this->redirect(array('item/edit', 'id' => $itemId));
                }
                catch(ServiceException $exception) {
                    $model->addError('projectId', $exception->getMessage());
                }
            }
        }
        
        $model->projectsList = $this->userProjectsListData;
        $model->projectId = Yii::app()->user->defaultProjectId;

        return $this->render('add', array(
                'model' => $model,
            )
        );
    }
    
    public function actionEdit($id) {
        $itemSevice = $this->ioc->create('IItemService');
        $itemServiceData = $itemSevice->getReport($id);
        if ($itemServiceData != null) {
            $model = AddItemForm::createByItemServiceData($itemServiceData);
            CVarDumper::dump($model, 10, true);
            CVarDumper::dump($model->priorityText, 10, true);
            CVarDumper::dump($model->defectTypeText, 10, true);
            CVarDumper::dump($model->itemKindText, 10, true);
            CVarDumper::dump($model->statusText, 10, true);
        }
    }
    
    public function actionSubscribers() {
        if ($this->request->isAjaxRequest) {
            $projectService = $this->ioc->create('IProjectService');
            $projectId = $this->request->getParam('project_id',null);
            
            if ($projectId == null) {
                $projectUsersList = array();
            } else {
                $projectUsersList = $projectService->getProjectUsers($projectId);
            }
            $this->renderJson(UserWrapper::getUsers($projectUsersList));
        }
    }
}

?>
