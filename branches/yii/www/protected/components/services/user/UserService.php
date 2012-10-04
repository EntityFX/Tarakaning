<?php

class UserService extends Service {

    const HASH_SALT = 'MOTPWBAH';

    private static $authTableName = 'USER';

    public function __construct() {
        parent::__construct();
        self::$authTableName = UserService::$authTableName;
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
                            "NICK"          => $userIdentity,
                            "PASSW_HASH"    => $this->generatePasswordHash($password),
                            "USR_TYP"       => $type,
                            "FRST_NM"       => htmlspecialchars($name, ENT_QUOTES),
                            "LAST_NM"       => htmlspecialchars($surname, ENT_QUOTES),
                            "SECND_NM"      => htmlspecialchars($secondName, ENT_QUOTES),
                            "EMAIL"         => $email
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
            if ($usr["NICK"] != "admin") {
                $this->db->createCommand()->delete(
                        self::$authTableName, 'USER_ID = :id', array(
                    ':id' => $id
                        )
                );
            } else {
                throw new ServiceException("Can't delete admin");
            }
        }
    }

    public function changeUserType($id, $type) {
        $this->changeBoolFieldForId($id, $type, 'USR_TYP');
    }

    public function activateById($id) {
        $this->changeBoolFieldForId($id, true, 'ACTIVE');
    }

    public function diactivateById($id) {
        $this->changeBoolFieldForId($id, false, 'ACTIVE');
    }

    private function changeBoolFieldForId($id, $type, $fieldName) {
        $id = (int) $id;
        $type = (bool) $type;

        if ($this->existsById($id)) {
            $this->db->createCommand()->update(
                    self::$authTableName, array(
                $fieldName => $type
                    ), 'USER_ID = :id', array(
                ':id' => $id
                    )
            );
        }
    }

    public function getAll() {
        return $this->db->createCommand()->select()
                        ->from(self::$authTableName)
                        ->order(UsersOrderFields::NICK_NAME)
                        ->queryAll();
    }

    public function getAllByFirstLetter($token) {
        $secureToken = strtr($token, array('%' => '\%', '_' => '\_'));
        return $this->db->createCommand()->select()
                        ->from(self::$authTableName)
                        ->where(
                                array('like', 'NICK', "$secureToken%")
                        )
                        ->queryAll();
    }

    public function getById($id) {
        $id = (int) $id;
        return $this->db->createCommand()
                        ->select()
                        ->from(self::$authTableName)
                        ->where('USER_ID=:id', array('id' => $id))
                        ->queryRow();
    }

    public function getByIdentity($user) {
        $user = (string) $user;
        return $this->db->createCommand()
                        ->select()
                        ->from(self::$authTableName)
                        ->where('NICK=:user', array('user' => $user))
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
                        ->select('NICK')
                        ->from(self::$authTableName)
                        ->where('NICK=:user', array('user' => $user))
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
                        ->select('USER_ID')
                        ->from(self::$authTableName)
                        ->where('USER_ID=:id', array('id' => $id))
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
        $newPasswordHash = $this->generatePasswordHash($newPassword);
        $usr = $this->getById($id);
        if ($usr != null) {
            if ($this->generatePasswordHash($oldPassword) != $usr["PASSW_HASH"]) {
                throw new ServiceException("Старый пароль неверный", 2);
            } else {
                $id = (int) $id;
                $this->db->createCommand()
                        ->update(
                                self::$authTableName, array(
                            'PASSW_HASH' => $newPasswordHash
                                ), 'USER_ID = :id', array(
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
            $newPasswordHash = $this->generatePasswordHash($newPassword);
            $id = (int) $id;
            $this->db->createCommand()
                    ->update(
                            self::$authTableName, array(
                        'PASSW_HASH' => $newPasswordHash
                            ), 'USER_ID = :id', array(
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
    private function generatePasswordHash($newPassword) {
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
                    'FRST_NM' => htmlspecialchars($name),
                    'LAST_NM' => htmlspecialchars($surname),
                    'SECND_NM' => htmlspecialchars($secondName),
                    'EMAIL' => htmlspecialchars($email)
                        ), 'USER_ID = :id', array(
                    ':id' => $id
                        )
        );
    }

}

class UsersOrderFields extends AEnum {

    const NICK_NAME = "NICK";

}

?>
