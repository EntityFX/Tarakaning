<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProjectController
 *
 * @author EntityFX
 */
class ProjectController extends ContentControllerBase {
    
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array(
                'deny',
                'users' => array('?')
            )
        );
    }

    public function actionIndex() {
        $projectSevice = $this->ioc->create('IProjectService');
        
        $projectsList = $projectSevice->getUserProjectsInfo(
                Yii::app()->user->id,
                new MyProjectsFieldsENUM(),
                new MySQLOrderEnum()
        );
        
        return $this->render(
                'projects', 
                array(
                    'userProjectsDataProvider' => self::prepareProjectsDataProvider($projectsList),
                    'userProjectsPaginator' => null
                )
        );
    }
    
    /**
     *
     * @uses IProjectService
     * 
     * @return string 
     */
    public function actionAdd() {
        $model = new AddProjectForm();
        
        $formData = $this->request->getPost("AddProjectForm");
        if (isset($formData)) {
            $model->attributes = $formData;
            if ($model->validate()) {
                $projectSevice = $this->ioc->create('IProjectService');

                $id = $projectSevice->addProject(
                    Yii::app()->user->id,
                    $model->name,
                    $model->description
                );
                
                $this->redirect(array('project/index'));
            }
        }
        
        return $this->render('add', array('model' => $model));
    }
    
    public function actionEdit($id) {
        $projectSevice = $this->ioc->create('IProjectService');
        
        $projectServiceData = $projectSevice->getProjectById($id);
        if ($projectServiceData != null) {
            $model['project'] = AddProjectForm::createByProjectServiceData($projectServiceData);
        } else {
            $this->redirect(array('project/index'));
        }
        
        if ($this->request->isPostRequest) {
            $postData = $this->request->getPost('AddProjectForm');
            if ($postData !== null) {
                $model['project']->attributes = $postData;
                if ($model['project']->validate()) {
                    try {
                        $projectSevice->editById(
                                $model['project']->id, 
                                $model['project']->authorId, 
                                $model['project']->name, 
                                $model['project']->description
                        );
                        $this->redirect(array('project/edit', 'id' => $id));
                    } catch (ServiceException $exception) {
                        $model->addError('name', $exception->getMessage());
                    }
                }
            }
        }
        
        $isProjectOwner = self::isProjectOwner($model['project']->authorId);

        $subscribesService = $this->ioc->create('ISubscribeService');
        $requestsCount = $subscribesService->getProjectRequestsCount($model['project']->id);
        $usersCount = $projectSevice->getProjectUsersInfoCount($model['project']->id);
        
        return $this->render('edit', array(
                'canEditItem' => $canEditItem,
                'model' => $model,
                'isProjectOwner' => $isProjectOwner,
                'requestsCount' => $requestsCount,
                'usersCount' => $usersCount
            )
        );
    }
    
    private static function isProjectOwner($projectId) {
        return $projectId == Yii::app()->user->id;
    }
    
    /**
     *
     * @param array $projectsList 
     * @return IDataProvider
     */
    private static function prepareProjectsDataProvider(array &$projectsList) {
        $projectsDataProvider = new CArrayDataProvider(
                $projectsList == null ? array() : $projectsList,
                array(
                    'id' => ProjectAndItemsView::PROJECT_ID_FIELD,
                    'sort' => array(
                        'attributes'=> array(
                            MyProjectsFieldsENUM::PROJECT_NAME,
                            MyProjectsFieldsENUM::DESCRIPTION,
                            MyProjectsFieldsENUM::COUNT_REQUESTS,
                            MyProjectsFieldsENUM::COUNT_USERS,
                            MyProjectsFieldsENUM::CREATE_DATE,
                        )
                    )
                )
        );
        
        $projectsDataProvider->setTotalItemCount(5);
        return $projectsDataProvider;
    }
}

?>
