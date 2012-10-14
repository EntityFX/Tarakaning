<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Администратор
 */
interface IProjectService {

    /**
     * Добавление нового проекта. Создано 27.01.2011.
     * @param $userId - id пользователя, создавшего проект.
     * @param $projectName - название проекта.
     * @param $description - описание проекта.
     * @return int Новый идентификатор проекта. 
     * 
     * @todo 1) проверку существования пользователя.<br />
     * 2) при добавлении проекта должно происходить добавление в таблицу истории.
     */
    public function addProject($userID, $projectName, $description);

    /**
     * Проверяет существует ли проект с ананлогичным названием.
     * @param string $name
     */
    public function existsByName($name);

    /**
     * Обновление имени проекта. Обновить имя может только создатель проекта. Создано 28.01.2011.
     * @param int $userID - id пользователя, создавшего проект.
     * @param string $projectNewName - новое название проекта.
     * @param int $projectID - id проекта, подлежащего изменению названия.
     * 
     */
    public function updateProjectDataById($projectID, $userID, $projectNewName, $newDescription);

    /**
     * Проверяется является ли пользователь автором проекта.
     * @param int $userID - id пользователя, создавшего проект.
     * @param int $projectID - id проекта, подлежащего изменению названия.
     */
    public function isOwner($userID, $projectID);

    /**
     * Удаление проекта. Удалять проект может только создатель. Создано 28.01.2011.
     * @param int $userID
     * @param int $projectID
     * 
     * @todo добавить проверку пользователя - является ли администратором. Тогда можно будет и ему удалять.
     */
    public function deleteById($userID, $projectID);

    /**
     * 
     * Удаляет проекты заданного пользователя
     * @param int $userID Идентификатор пользователя
     * @param array $projectsList Список проектов на удаление (ключ - идентификатор)
     */
    public function deleteProjectsFromList($userID, $projectsList);

    /**
     * Получение списка всех проектов. Создано 28.01.2011.
     * 
     * @param int $startIndex - индекс с которого нужно показывать результаты поиска.
     * @param int $maxCount - максимальное количество результатов поиска.
     * @return array 
     */
    public function getAll();

    /**
     * Возвращает проект по его идентификатору
     * 
     * @param int $projectID
     * @return array
     */
    public function getProjectById($projectID);

    /**
     * 
     * Возращает проекты по списку идентификаторов
     * @param array $projectIDList
     */
    public function getProjectsByList($projectIDList);

    /**
     * 
     * Возращает проекты по списку идентификаторов
     * @param array $projectIDList
     */
    public function getProjectsWithSubscribesByList($userID, $projectIDList);

    public function searchProjectsUsingLikeCount($pattern);

    public function searchProjectsUsingLike($userID, $pattern, $page = 0, $size = 10);

    public function getUserProjectsInfo($userId, MyProjectsFieldsENUM $orderField, MySQLOrderEnum $direction, $page = 0, $size = 10);

    public function getUserProjectsInfoCount($userId);

    public function getMemberProjects($userId, MyProjectsFieldsENUM $orderField, MySQLOrderEnum $direction, $page = 0, $size = 10);

    public function getMemberProjectsCount($userId);

    /**
     * Returns total information about users
     * 
     * @param int $projectID Project identificator
     * @return array Information list about users 
     */
    public function getProjectUsersInfo($projectID);

    /**
     * Получить число участников в проекте
     * 
     * @param int $projectID Идентификатор проекта
     * @return int Количетво участников в проекте 
     */
    public function getProjectUsersInfoCount($projectID);

    public function getProjectsUsersInfoPagOrd($projectID, ProjectFieldsUsersInfoENUM $orderField, MySQLOrderEnum $direction, $page = 0, $size = 15);

    public function getUserProjects($userId);

    /**
     * 
     * Получить всех участников проекта (и владельца).
     * @param int $projectID
     */
    public function getProjectUsers($projectID);

    /**
     * Получение списка проектов по фильтру.
     * @param string $sortType - фильтр поиска. "date", "name".
     * @param bool $ask - по возрастанию или по убыванию.
     * @param int $startIndex - вывод резултатов с данной позиции.
     * @param int $maxCount - количество результатов вывода.
     */
    public function getSortedProjects($sortType = "date", $ask = true, $startIndex = 0, $maxCount = 20);

    /**
     * Проверка существования проекта.
     * @param int $projectID - id проекта.
     */
    public function existsById($projectID);

    public function getOwnerID($projectID);

}
?>
