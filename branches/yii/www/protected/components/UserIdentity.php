<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 * 
 * @property int $id
 * @property string $email 
 * @property string $surname
 * @property string $lastname 
 * @property int $defaultProjectId
 */
final class UserIdentity extends CUserIdentity {

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
            $this->setState('email', $userData[UserTable::EMAIL_FIELD]);
            $this->setState('firstname', $userData[UserTable::FRST_NM_FIELD]);
            $this->setState('secondName', $userData[UserTable::SECND_NM_FIELD]);
            $this->setState('lastName', $userData[UserTable::LAST_NM_FIELD]);
            $this->setState('defaultProjectId', (int) $userData[UserTable::DFLT_PROJ_ID_FIELD]);
            $this->setState('id', (int) $userData[UserTable::USER_ID_FIELD]);
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
        return $this->id;
    }

    public function getName() {
        return $this->username;
    }

    private function validatePassword($userDataPassword) {
        return $userDataPassword === UserService::generatePasswordHash($this->password);
    }

}