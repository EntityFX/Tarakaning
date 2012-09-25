<?php

Loader::LoadPageController('InfoBasePage');

Loader::LoadModel('Subscribes/SubscribesModel');
Loader::LoadModel('Requests/RequestModel');
Loader::LoadModel('Projects/ProjectSubscribesDetailENUM');
Loader::LoadModel('Projects/ProjectFieldsENUM');

Loader::LoadControl('TarakaningULListPager');
Loader::LoadSystem('controls', 'Orderer/Orderer');

class MyProjectsDetailPage extends InfoBasePage {

    private $_projectData;
    private $_projectUsers;

    /**
     * Число участников в проекте
     * @var int 
     */
    private $_projectUsersCount;
    private $_projectDetailPage;
    private $_myProjectsInfoPaginator;
    private $_orderer;
    private $_orderData;
    private $_subscribes;
    private $_subscribesRequestData;
    private $_subscribesRequestPaginator;
    private $_subscribesRequestOrderer;
    private $_isOwner;
    private $_projectOperation;
    private $_subscribesCount;

    protected function onInit() {
        parent::onInit();
        $this->_projectOperation = new ProjectsModel();
        $this->_projectData = $this->_projectOperation->getProjectById($this->_parameters[0]);

        //TODO: !!!
        var_dump($this->_projectOperation->getNextByUserID($this->_projectData['ProjectID'], $this->_userInfo['USER_ID']));

        $this->_subscribes = new SubscribesModel();

        if ($this->_projectData != null) {
            if ($this->request->isPost()) {
                if ($this->request->getPost("delete_member", null) != null) {
                    $this->deleteSelectedMembers();
                }
                if ($this->request->getPost("assign_subscribes", null) != null) {
                    $this->commitSelectedRequests();
                }
            }
        } else {
            $this->navigate('/my/projects/');
        }

        $projectID = $this->_projectData['ProjectID'];
        $this->_subscribesCount = $this->_subscribes->getProjectSubscribesCount($projectID);

        $this->_isOwner = $this->isProjectOwner();

        $this->_projectUsersCount = $this->_projectOperation->getProjectUsersInfoCount($this->_parameters[0]);

        $this->_myProjectsInfoPaginator = new TarakaningULListPager($this->_projectUsersCount);
        $this->_orderer = new Orderer(new ProjectFieldsUsersInfoENUM());
        $this->_orderData = $this->_orderer->getNewUrls();

        $this->_projectUsers = $this->_projectOperation->getProjectsUsersInfoPagOrd(
                $this->_parameters[0], new ProjectFieldsUsersInfoENUM($this->_orderer->getOrderField()), new MySQLOrderENUM($this->_orderer->getOrder()), $this->_myProjectsInfoPaginator->getOffset(), $this->_myProjectsInfoPaginator->getSize()
        );

        $this->_subscribesRequestPaginator = new TarakaningULListPager($this->_subscribes->getProjectSubscribesCount($projectID), 'subscribesPage');
        $this->_subscribesRequestOrderer = new Orderer(new ProjectSubscribesDetailENUM(), 'orderBySubscribes');

        $this->_subscribesRequestData = $this->_subscribes->getProjectSubscribes(
                $projectID, $this->_subscribesRequestOrderer, $this->_subscribesRequestPaginator
        );
    }

    protected function doAssign() {
        parent::doAssign();
        $this->_smarty->assign("PROJECT_USERS", $this->_projectUsers);
        $this->_smarty->assign("PROJECT_USERS_COUNT", $this->_projectUsersCount);
        $this->_smarty->assign("Project", $this->_projectData);
        $this->_smarty->assign("MY_PROJECT_DETAIL_PAGINATOR", $this->_myProjectsInfoPaginator->getHTML());
        $this->_smarty->assign("MY_PROJECT_ORDERER", $this->_orderData);

        $this->_smarty->assign('PROJECT_SUBSCRIBES_REQUEST', $this->_subscribesRequestData);
        $this->_smarty->assign('PROJECT_SUBSCRIBES_REQUEST_PAGINATOR', $this->_subscribesRequestPaginator != null ? $this->_subscribesRequestPaginator->getHTML() : null);
        $this->_smarty->assign('PROJECT_SUBSCRIBES_ORDERER', $this->_subscribesRequestOrderer != null ? $this->_subscribesRequestOrderer->getNewUrls() : null);
        $this->_smarty->assign('IS_OWNER', $this->_isOwner);

        $this->_smarty->assign('COUNT_SUBSCRIBES', $this->_subscribesCount);

        $newProjectOK = $this->_controller->error->getErrorByName("newProjectOK");
        if ($newProjectOK) {
            $this->_smarty->assign("GOOD", true);
        }
    }

    private function deleteSelectedMembers() {
        $checkboxes = $this->request->getPost("del_i");
        $this->_subscribes->deleteProjectMembers(
                Serialize::SerializeForStoredProcedure($checkboxes), $this->_userInfo['USER_ID'], $this->_projectData['ProjectID']
        );
    }

    private function deleteSelectedRequests() {
        
    }

    private function commitSelectedRequests() {
        $checkboxes = $this->request->getPost("sub_i");
        $subscribesOperation = new RequestModel();
        $subscribesOperation->acceptRequest(
                Serialize::SerializeForStoredProcedure($checkboxes), $this->_userInfo['USER_ID'], $this->_projectData['ProjectID']
        );
    }

    /**
     * 
     * Провера, является ли участником проекта
     * @return boolean
     */
    private function isProjectOwner() {
        if ($this->_projectData != null) {
            if ($this->_userInfo['USER_ID'] == $this->_projectData['OwnerID']) {
                return true;
            }
        }
        return false;
    }

}

?>