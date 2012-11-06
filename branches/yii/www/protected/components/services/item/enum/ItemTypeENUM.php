<?php

final class ItemTypeENUM extends AEnum {

    const CRASH = 'Crash';
    const COSMETIC = 'Cosmetic';
    const ERROR_HANDLE = 'Error Handling';
    const FUNCTIONAL = 'Functional';
    const MINOR = 'Minor';
    const MAJOR = 'Major';
    const SETUP = 'Setup';
    const BLOCK = 'Block';

    public function __construct($value = self::ERROR_HANDLE) {
        parent::__construct($value);
    }

    public function getNormalized() {
        return array(
            self::CRASH => "Крах",
            self::COSMETIC => "Ошибка интерфейса",
            self::ERROR_HANDLE => "Исключение",
            self::FUNCTIONAL => "Функциональный",
            self::MINOR => "Незначительный",
            self::MAJOR => "Значительный",
            self::SETUP => "Ошибка инсталляции",
            self::BLOCK => "Блокирующая ошибка"
        );
    }

}

?>
