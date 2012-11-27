<?php

class UserService extends ServiceBase implements IUserService {

    const HASH_SALT = 'MOTPWBAH';

    private static $authTableName = 'USER';

    public function __construct() {
        parent::__construct();
        self::$authTableName = UserTable::NAME;
    }

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
    public function create($userIdentity, $password, $type = 0, $name = "", $surname = "", $secondName = "", $email = "") {
        $hash = "";
        if ($userIdentity != "") {
            if (!self::checkMail($userIdentity)) {
                throw new ServiceException("Неверный формат почты", 1);
            }
        }
        if ($this->existsByIdentity($userIdentity)) {
            throw new ServiceException("Пользователь уже существует", 0);
        }
        if (!self::checkPassword($password)) {
            throw new ServiceException("Пароль должен быть не менее 7 символов (для безопасности)", 2);
        }
        if ($email != "") {
            if (!self::checkMail($email)) {
                throw new ServiceException("Неверный формат почты", 1);
            }
        }
        $this->db->createCommand()
                ->insert(
                        self::$authTableName, array(
                            UserTable::NICK_FIELD          => $userIdentity,
                            UserTable::PASSW_HASH_FIELD    => self::generatePasswordHash($password),
                            UserTable::USR_TYP_FIELD       => $type,
                            UserTable::FRST_NM_FIELD       => htmlspecialchars($name, ENT_QUOTES),
                            UserTable::LAST_NM_FIELD       => htmlspecialchars($surname, ENT_QUOTES),
                            UserTable::SECND_NM_FIELD      => htmlspecialchars($secondName, ENT_QUOTES),
                            UserTable::EMAIL_FIELD         => $email
                        )
        );
        return (int)$this->db->getLastInsertID();
    }

    /**
     * Deletes user by Id
     * 
     * @param int $id User id
     * @throws ServiceException If user is admin
     */
    public function deleteById($id) {
        $id = (int) $id;
        $usr = $this->getById($id);
        if ($usr != null) {
            if ($usr[UserTable::NICK_FIELD] != "admin") {
                $this->db->createCommand()->delete(
                        self::$authTableName, UserTable::USER_ID_FIELD . ' = :id', array(
                            ':id' => $id
                        )
                );
            } else {
                throw new ServiceException("Can't delete admin");
            }
        }
    }

    public function changeUserType($id, $type) {
        $this->changeBoolFieldForId($id, $type, UserTable::USR_TYP_FIELD);
    }

    public function activateById($id) {
        $this->changeBoolFieldForId($id, true, UserTable::ACTIVE_FIELD);
    }

    public function diactivateById($id) {
        $this->changeBoolFieldForId($id, false, UserTable::ACTIVE_FIELD);
    }

    private function changeBoolFieldForId($id, $type, $fieldName) {
        $id = (int) $id;
        $type = (bool) $type;

        if ($this->existsById($id)) {
            $this->db->createCommand()->update(
                    self::$authTableName, array(
                        $fieldName => $type
                    ), UserTable::USER_ID_FIELD . ' = :id', array(
                        ':id' => $id
                    )
            );
        }
    }

    public function getAll() {
        return $this->db->createCommand()->select()
                        ->from(self::$authTableName)
                        ->order(UsersOrderFieldsEnum::NICK_NAME)
                        ->queryAll();
    }

    public function getAllByFirstLetter($token) {
        $secureToken = strtr($token, array('%' => '\%', '_' => '\_'));
        return $this->db->createCommand()->select()
                        ->from(self::$authTableName)
                        ->where(
                                array('like', UserTable::NICK_FIELD, "$secureToken%")
                        )
                        ->queryAll();
    }

    public function getById($id) {
        $id = (int) $id;
        return $this->db->createCommand()
                        ->select()
                        ->from(self::$authTableName)
                        ->where(UserTable::USER_ID_FIELD . '=:id', array(':id' => $id))
                        ->queryRow();
    }

    public function getByIdentity($user) {
        $user = (string) $user;
        return $this->db->createCommand()
                        ->select()
                        ->from(self::$authTableName)
                        ->where(UserTable::NICK_FIELD . '=:user', array(':user' => $user))
                        ->queryRow();
    }

    /**
     * Проверить существование логина
     *
     * @param string $name Заголовок группы
     * @return bool
     */
    public function existsByIdentity($user) {
        $user = (string) $user;
        return $this->db->createCommand()
                        ->select(UserTable::NICK_FIELD)
                        ->from(self::$authTableName)
                        ->where(UserTable::NICK_FIELD . '=:user', array('user' => $user))
                        ->queryScalar() !== false ? true : false;
    }

    /**
     * Проверить существование по ID
     *
     * @param int $name ID пользователя
     * @return bool
     */
    public function existsById($id) {
        $id = (int) $id;
        return $this->db->createCommand()
                        ->select(UserTable::USER_ID_FIELD)
                        ->from(self::$authTableName)
                        ->where(UserTable::USER_ID_FIELD . '=:id', array('id' => $id))
                        ->queryScalar() !== false ? true : false;
    }

    /**
     * Проверка формата почты
     *
     * @param string $mail
     */
    public static function checkMail($mail) {
        if (preg_match("/^([a-zA-Z0-9]([a-zA-Z0-9\._-]*)@)([a-zA-Z0-9_-]+\.)*[a-z]{2,}$/", $mail) == 1) {
            return true;
        } else {
            return false;
        }
    }

    public static function checkPassword($password) {
        if (strlen($password) >= 7) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Changes old password with new for user and returns its hash
     * 
     * @param int $id User identificator 
     * @param string $oldPassword old user's password
     * @param string $newPassword new password
     * @return string
     * @throws ServiceException Wrong old password or length of new one
     */
    public function changePassword($id, $oldPassword, $newPassword) {
        if (!self::checkPassword($newPassword)) {
            throw new ServiceException("Пароль должен быть не менее 7 символов (для безопасности)", 2);
        }
        $newPasswordHash = self::generatePasswordHash($newPassword);
        $usr = $this->getById($id);
        if ($usr != null) {
            if (self::generatePasswordHash($oldPassword) != $usr[UserTable::PASSW_HASH_FIELD]) {
                throw new ServiceException("Старый пароль неверный", 2);
            } else {
                $id = (int) $id;
                $this->db->createCommand()
                        ->update(
                                self::$authTableName, array(
                                    UserTable::PASSW_HASH_FIELD => $newPasswordHash
                                ), UserTable::USER_ID_FIELD . ' = :id', array(
                                    ':id' => $id
                                )
                );
            }
        }
        return $newPasswordHash;
    }

    /**
     * Changes old password for user and returns a new random password
     * 
     * @param int $id User identificator
     * @param int $length Password length for new random password
     * @return string 
     */
    public function setRandomPasswordById($id, $length) {
        $usr = $this->getById($id);
        if ($usr != null) {
            $newPassword = "";
            mt_srand(time());
            for ($it = 0; $it < $length; $it++) {
                switch (mt_rand(0, 2)) {
                    case 0:
                        $newPassword.=chr(mt_rand(0x61, 0x7A));
                        break;
                    case 1:
                        $newPassword.=chr(mt_rand(0x41, 0x5A));
                        break;
                    case 2:
                        $newPassword.=chr(mt_rand(0x30, 0x39));
                        break;
                }
            }
            $newPasswordHash = self::generatePasswordHash($newPassword);
            $id = (int) $id;
            $this->db->createCommand()
                    ->update(
                            self::$authTableName, array(
                                UserTable::PASSW_HASH_FIELD => $newPasswordHash
                            ), UserTable::USER_ID_FIELD . ' = :id', array(
                                ':id' => $id
                            )
            );
        }
        return $newPassword;
    }

    /**
     * Generates md5 hash with salt
     * 
     * @param string Password to hash
     * @return string 
     */
    public static function generatePasswordHash($newPassword) {
        return md5(md5($newPassword) . self::HASH_SALT);
    }

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
    public function updateDataById($id, $name, $surname, $secondName, $email) {
        if ($email != "") {
            if (!self::checkMail($email)) {
                throw new ServiceException("Неверный формат почты", 1);
            }
        }
        $id = (int) $id;
        $this->db->createCommand()->
            update(
                self::$authTableName, array(
                    UserTable::FRST_NM_FIELD => htmlspecialchars($name),
                    UserTable::LAST_NM_FIELD => htmlspecialchars($surname),
                    UserTable::SECND_NM_FIELD => htmlspecialchars($secondName),
                    UserTable::EMAIL_FIELD => htmlspecialchars($email)
                ), UserTable::USER_ID_FIELD . ' = :id', array(
                    ':id' => $id
                )
            );
    }

}

class UsersOrderFieldsEnum extends AEnum {

    const NICK_NAME = UserTable::NICK_FIELD;

}

?>
