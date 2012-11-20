<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
final class UserIdentity extends CUserIdentity {

    /**
     *
     * @var int ID 
     */
    private $_userId;

    /**
     *
     * @var string 
     */
    private $_surname;

    /**
     *
     * @var string 
     */
    private $_lastName;

    /**
     *
     * @var int Id of default project for current user 
     */
    private $_defaultProjectId;

    /**
     * 
     * @var string E-mail address
     */
    private $_email;

    /**
     * Performs authentification
     * 
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        /**
         * @var IUserService
         */
        $userService = Ioc::create()->create('IUserService');
        $userData = $userService->getByIdentity($this->username);
        if ($userData === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if (!$this->validatePassword($userData[UserTable::PASSW_HASH_FIELD])) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->errorCode = self::ERROR_NONE;
            $this->password = null;
            $this->setUserIdentityFields($userData);
            $this->setState('email', $userData[UserTable::EMAIL_FIELD]);
            $this->setState('surname', $userData[UserTable::SECND_NM_FIELD]);
            $this->setState('lastname', $userData[UserTable::LAST_NM_FIELD]);
            $this->setState('defaultProjectId', (int) $userData[UserTable::DFLT_PROJ_ID_FIELD]);
        }
        return !$this->errorCode;
    }

    /**
     * Returns the unique identifier for the identity.
     * The default implementation simply returns {@link username}.
     * This method is required by {@link IUserIdentity}.
     * @return string the unique identifier for the identity.
     */
    public function getId() {
        return $this->_userId;
    }

    public function getSurname() {
        return $this->_surname;
    }

    public function getLastName() {
        return $this->_lastName;
    }

    public function getDefaultProjectId() {
        return $this->_defaultProjectId;
    }

    public function getEmail() {
        return $this->_email;
    }

    public function getName() {
        return $this->username;
    }

    private function validatePassword($userDataPassword) {
        return $userDataPassword === UserService::generatePasswordHash($this->password);
    }

}