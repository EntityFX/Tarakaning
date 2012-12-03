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
        
        $paginator = new CPagination();
        $paginator->itemCount = 50;
        $paginator->pageSize = 10;
        
        $widget = $this->beginWidget("CLinkPager", array('pages' => $paginator));
        //var_dump(self::prepareProjectsDataProvider($projectsList));
        return $this->render(
                'projects', 
                array(
                    'userProjectsDataProvider' => self::prepareProjectsDataProvider($projectsList),
                    'userProjectsPaginator' => $paginator
                )
        );
    }
    
    /**
     *
     * @param array $projectsList 
     * @return IDataProvider
     */
    private static function prepareProjectsDataProvider(array &$projectsList) {
        
        $pagination = new CPagination(50);
        $pagination->pageSize = 2;
        return new CArrayDataProvider(
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
                    ),
                    'pagination'=>$pagination
                )
        );
    }
}

?>
