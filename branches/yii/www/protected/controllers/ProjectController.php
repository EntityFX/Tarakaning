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
     * @return type 
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
                    $model->projectName,
                    $model->projectDescription
                );
                
                //var_dump($id);
                
                $this->redirect(array('project/index'));
            }
        }
        
        return $this->render('add', array('model' => $model));
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
