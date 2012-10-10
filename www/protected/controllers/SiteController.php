<?php

class SiteController extends EntityFxControllerBase {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {

        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionIndex() {
        $model = new LoginForm;
        $this->render('index', array('model' => $model));
    }

    public function actionTest() {
        //$userService = new UserService();
        //var_dump(
        /* $userService->getById(1), 
          $userService->getByIdentity("GreenDragon"),
          $userService->existsByIdentity("GreenDragon"),
          $userService->deleteById(16),
          $userService->getAll(),
          $userService->changeUserType(15, true),
          $userService->diactivateById(15), */
        //$userService->getAllByFirstLetter('v')
        //$userService->updateDataById(15, 'Hrenov', 'Hren', "Hrenovich", 'tym_s@mail.ru')
        //$userService->setRandomPasswordById(15,9)
        //$userService->changePassword(15,'5BLM7iorC','asdfgaa')
        //$userService->create('fg@w.ru', '1234567',1,'132','sdfd','fthfgh','hgfh@sfgf.ty')
        //);

        $projectService = new ProjectService();
        //var_dump($projectService->addProject(1, 'Hi', 'Hello'));
        //var_dump($projectService->addProject(1, '<b>fuck</b>', 'Hello'));
        //var_dump($projectService->deleteById(2, 9));
        //var_dump($projectService->deleteProjectsFromList(1, array(10, 11)));
        //CVarDumper::dump($projectService->existsById(3),10,true);
        //CVarDumper::dump($projectService->existsByName('M1'),10,true);
        //CVarDumper::dump($projectService->getAll(),10,true);
        /* CVarDumper::dump(
          $projectService->getMemberProjects(
          20,
          new MyProjectsFieldsENUM(),
          new MySQLOrderENUM()
          ),10,true
          ); */
        //CVarDumper::dump($projectService->getMemberProjectsCount(20),10,true);
        //CVarDumper::dump($projectService->getOwnerID(3),10,true);
        //CVarDumper::dump($projectService->getProjectById(1),10,true);
        //CVarDumper::dump($projectService->getProjectUsers(4),10,true);
        //CVarDumper::dump($projectService->getProjectUsers(4),10,true);
        //CVarDumper::dump($projectService->getProjectUsersInfo(4),10,true);
        //CVarDumper::dump($projectService->getProjectUsersInfoCount(4),10,true);
        //CVarDumper::dump($projectService->getProjectsByList(array(3, 4)),10,true);
        /* CVarDumper::dump(
          $projectService->getProjectsUsersInfoPagOrd(
          4, new ProjectFieldsUsersInfoENUM(), new MySQLOrderENUM(MySQLOrderENUM::DESC), 0, 4
          ), 10, true); */
        /* CVarDumper::dump(
          $projectService->getProjectsWithSubscribesByList(2, array(3, 4)), 10, true); */
        //CVarDumper::dump($projectService->getSortedProjects(), 10, true);
        //CVarDumper::dump($projectService->getUserProjects(1), 10, true);
        /*CVarDumper::dump(
                $projectService->getUserProjectsInfo(
                        1, new MyProjectsFieldsENUM(), new MySQLOrderENUM()
                ), 10, true);*/
        //CVarDumper::dump($projectService->getUserProjectsInfoCount(1), 10, true);
        //CVarDumper::dump($projectService->isOwner(1, 22), 10, true);
        //CVarDumper::dump($projectService->searchProjectsUsingLike(1, 'T'), 10, true);
        //CVarDumper::dump($projectService->searchProjectsUsingLikeCount(1, 'T'), 10, true);
        //CVarDumper::dump($projectService->updateProjectDataById(3, 22, 'GGG', 'GGG'), 10, true);
    }

}