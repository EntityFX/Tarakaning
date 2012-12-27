<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
abstract class EntityFxControllerBase extends CController {

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    /**
     * @var CApplication 
     */
    protected $application;
    
    /**
     *
     * @var Phemto 
     */
    protected $ioc;
    
    /**
     *
     * @var CHttpRequest 
     */
    protected $request;

    public function init() {
        parent::init();
        $this->application = YII::app();
        $this->request = $this->application->request;
        $this->ioc = Ioc::create();
    }
    
    protected function prepareModelList(array &$models) {
        foreach ($models as $modelName => $modelItem) {
            $model[$modelName] = $modelItem['model'];
        }
        return $model;
    }
    
    protected function updateModel(array &$modelList) {
        if ($this->request->isPostRequest) {
            foreach ($modelList as $modelName => $item) {
                $modelData = $this->request->getPost($modelName);
                if (isset($modelData)) {
                    $model = $item['model'];
                    $model->attributes = $modelData;

                    if ($model->validate()) {
                        $performFunctionName = $item['performFunction'];

                        $this->$performFunctionName($model);
                    }
                }
            }
        }
    }
    
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
    
    protected function renderJson($data) {
        $this->layout = false;
        header('Content-type: application/json');
        echo CJSON::encode($data);
        Yii::app()->end();
    }

}