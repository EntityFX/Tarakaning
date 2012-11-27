<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ContainerControllerBase
 *
 * @author Artem
 */
class ContentControllerBase extends EntityFxControllerBase {

    /**
     *
     * @var array List of user's projects 
     */
    public $userProjectsListData = array();
    
    protected $currentProjectId;

    public function init() {
        parent::init();
        $projectService = $this->ioc->create('IProjectService');

        $userProjectsList = $projectService->getUserProjects(Yii::app()->user->id);
        if ($userProjectsList != null) {
            $this->currentProjectId = $this->request->getParam('project_id', Yii::app()->user->defaultProjectId);
            if ($this->currentProjectId !=null) {
                Yii::app()->user->defaultProjectId = $this->currentProjectId;
            } else {
                Yii::app()->user->defaultProjectId = $userProjectsList[0]['ProjectID'];
                $this->currentProjectId = Yii::app()->user->defaultProjectId;
            }
            $this->userProjectsListData = self::prepareUserProjectsListData(
                $userProjectsList
            );
        }
    }
    
    private function checkIsProjectInList(array &$projectList)
    {
        foreach($projectList as $projectItem)
        {
            if ((int)$projectItem['ProjectID']==$this->currentProjectID)
            {
                return true;
            }
        }
        return false;
    }

    /**
     * Prepares list with user projects for dropdown
     * 
     * @param array $userProjetsList
     * @return array 
     */
    private static function prepareUserProjectsListData(array $userProjetsList) {
        if ($userProjetsList == null) {
            return array();
        } else {
            return CHtml::listData(
                            $userProjetsList, 'ProjectID', 'Name'
            );
        }
    }

}

?>
