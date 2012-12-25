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
        
        
        $model->projectsList = $this->userProjectsListData;
        $model->projectId = Yii::app()->user->defaultProjectId;

        return $this->render('add', array(
                'model' => $model,
            )
        );
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
            $normalizedrezultList = self::getUsers($projectUsersList);
            $this->renderJson($normalizedrezultList);
        }
    }
    
    private static function getUsers(array &$projectUsersList) {
        foreach ($projectUsersList as $userItem) {
            $result[(int)$userItem["UserID"]] = $userItem["NickName"];
        }
        return $result;
    }
}

?>
