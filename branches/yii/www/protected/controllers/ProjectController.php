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

    public function filters() {
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

        $projectsArrayProvider = self::prepareProjectsDataProvider($projectSevice->getUserProjectsInfoCount(Yii::app()->user->id));
        
        $projectsList = $projectSevice->getUserProjectsInfo(
                Yii::app()->user->id, 
                new MyProjectsFieldsENUM(
                        $projectsArrayProvider->getSortField() === '' ?  
                        MyProjectsFieldsENUM::PROJECT_NAME :
                        $projectsArrayProvider->getSortField()
                ), 
                new DBOrderENUM($projectsArrayProvider->getSortDirection()), 
                $projectsArrayProvider->getPagination()->getOffset(), 
                $projectsArrayProvider->getPagination()->getPageSize()
        );
        
        $projectsArrayProvider->setData($projectsList);
        return $this->render('projects', array(
                    'userProjectsDataProvider' => $projectsArrayProvider,
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

                //var_dump($id);
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
    private static function prepareProjectsDataProvider($count) {
        $projectsDataProvider = new SimpleArrayProvider(
                        $count,
                        array(
                            'id' => ProjectAndItemsView::PROJECT_ID_FIELD,
                            'sort' => array(
                                'attributes' => array(
                                    MyProjectsFieldsENUM::PROJECT_NAME,
                                    MyProjectsFieldsENUM::DESCRIPTION,
                                    MyProjectsFieldsENUM::COUNT_REQUESTS,
                                    MyProjectsFieldsENUM::COUNT_USERS,
                                    MyProjectsFieldsENUM::CREATE_DATE,
                                )
                            ),
                        )
        );
        return $projectsDataProvider;
    }

}

class SimpleArrayProvider extends CDataProvider {
    private $sortField;
    private $sortDirection;
    
    
    /**
     * @var string the name of key field. Defaults to 'id'. If it's set to false,
     * keys of $rawData array are used.
     */
    public $keyField = 'id';
    public $rawData;

    protected function fetchData() {
        if (($pagination = $this->getPagination()) !== false) {
            $pagination->setItemCount($this->getTotalItemCount());
        }
        return $this->rawData;
    }

    /**
     * Fetches the data item keys from the persistent data storage.
     * @return array list of data item keys.
     */
    protected function fetchKeys() {
        if ($this->keyField === false)
            return array_keys($this->rawData);
        $keys = array();
        foreach ($this->getData() as $i => $data)
            $keys[$i] = is_object($data) ? $data->{$this->keyField} : $data[$this->keyField];
        return $keys;
    }

    /**
     * Calculates the total number of data items.
     * @return integer the total number of data items.
     */
    protected function calculateTotalItemCount() {
        return count($this->rawData);
    }

    /**
     * Constructor.
     * @param array $rawData the data that is not paginated or sorted. The array elements must use zero-based integer keys.
     * @param array $config configuration (name=>value) to be applied as the initial property values of this class.
     */
    public function __construct($count, $config = array()) {
        foreach ($config as $key => $value)
            $this->$key = $value;
        $this->setTotalItemCount((int)$count);
        $this->getPagination()->setItemCount($this->getTotalItemCount());
        list($this->sortField, $this->sortDirection) = explode(' ',$this->getSort()->getOrderBy());
    }
    
    public function getSortField() {
        return $this->sortField;
    }
    
    public function getSortDirection() {
        return $this->sortDirection === null ? DBOrderENUM::ASC : DBOrderENUM::DESC;
    }

}

?>
