<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Администратор
 */
interface IUserService {

    /**
     * Creates a new user and returns his id
     *
     * @param string $userIdentity User Identity (e-mail account)
     * @param string $password
     * @param int $type
     * @param string $name
     * @param string $surname
     * @param string $secondName
     * @param string $email
     * @return int
     * @throws ServiceException 
     */
    function create($userIdentity, $password, $type = 0, $name = "", $surname = "", $secondName = "", $email = "");

    /**
     * Deletes user by Id
     * 
     * @param int $id User id
     * @throws ServiceException If user is admin
     */
    function deleteById($id);

    function changeUserType($id, $type);

    function activateById($id);

    function diactivateById($id);

    function getAll();

    function getAllByFirstLetter($token);

    function getById($id);

    function getByIdentity($user);

    /**
     * Проверить существование логина
     *
     * @param string $name Заголовок группы
     * @return bool
     */
    function existsByIdentity($user);

    /**
     * Проверить существование по ID
     *
     * @param int $name ID пользователя
     * @return bool
     */
    function existsById($id);

    /**
     * Changes old password with new for user and returns its hash
     * 
     * @param int $id User identificator 
     * @param string $oldPassword old user's password
     * @param string $newPassword new password
     * @return string
     * @throws ServiceException Wrong old password or length of new one
     */
    function changePassword($id, $oldPassword, $newPassword);

    /**
     * Changes old password for user and returns a new random password
     * 
     * @param int $id User identificator
     * @param int $length Password length for new random password
     * @return string 
     */
    function setRandomPasswordById($id, $length);

    /**
     * Updates information about user
     * 
     * @param int $id User unique identificator
     * @param string $name User name
     * @param string $surname User surname
     * @param string $secondName User second name
     * @param string $email Correct user e-mail
     * @throws ServiceException If wrong mail name
     */
    function updateDataById($id, $name, $surname, $secondName, $email);
}

?>
