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
            $model['editItem'] = AddItemForm::createByItemServiceData($itemServiceData);
            $itemModel = $model['editItem'];
            $canEditItem = !$itemSevice->canEditStatus($itemModel->itemId, $itemModel->projectId);
            
            $commentService = $this->ioc->create('ICommentService');
        } else {
            $this->redirect(array('project/index'));
        }
        
        return $this->render('edit', array(
                'canEditItem' => $canEditItem,
                'model' => $model
            )
        );
    }
    
    public function actionViewMineItems() {
        $itemSevice = $this->ioc->create('IItemService');
        
        $userItemsDataProvider = self::prepareUserItemsDataProvider(
                $itemSevice->countReports(new ItemKindENUM(ItemKindENUM::ALL))
        );
                

        $userItemsDataProvider->setData(
            ItemWrapper::getUserItemListData(
                $itemSevice->getMyOrdered(
                    new ItemKindENUM(ItemKindENUM::ALL),
                    new ItemFieldsENUM(
                            $userItemsDataProvider->getSortField() === '' ? 
                            ItemFieldsENUM::ID :
                            $userItemsDataProvider->getSortField()
                    ),
                    new DBOrderEnum($userItemsDataProvider->getSortDirection())
                )
            )
        );
        
        $this->render(
                'viewMineItems',
                array(
                    'userItemsDataProvider' => $userItemsDataProvider
                )
        );
    }
    
    public function actionViewProjectItems() {
        $this->render('viewProjectItems');
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
    
    /**
     * Preapres data provider for users's projects
     * @param array $projectsList 
     * @return IDataProvider
     */
    private static function prepareUserItemsDataProvider($count) {
        $projectsDataProvider = new SimpleArrayDataProvider(
                        $count,
                        array(
                            'id' => 'userItems',
                            'sort' => array(
                                'attributes' => array(
                                    ItemFieldsENUM::ID,
                                    ItemFieldsENUM::KIND,
                                    ItemFieldsENUM::STATUS,
                                    ItemFieldsENUM::TITLE,
                                    ItemFieldsENUM::PRIORITY,
                                    ItemFieldsENUM::ASSIGNED_NICK_NAME,
                                    ItemFieldsENUM::TIME
                                )
                            ),
                        )
        );
        return $projectsDataProvider;
    }
}

?>
