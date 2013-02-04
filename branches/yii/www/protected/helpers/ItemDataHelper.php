<?php

/**
 * Description of ItemDataHelper
 *
 * @author EntityFx
 */
class ItemDataHelper {

    public static function getItemsTypeList() {
        return array(
            ItemDBKindENUM::TASK => Yii::t('global', '* Задача'),
            ItemDBKindENUM::DEFECT => Yii::t('global', '* Дефект')
        );
    }

    public static function getDefectTypeList() {
        return array(
            ItemTypeENUM::CRASH => Yii::t('global', '* Крах'),
            ItemTypeENUM::COSMETIC => Yii::t('global', '* Ошибка в интерфейсе'),
            ItemTypeENUM::ERROR_HANDLE => Yii::t('global', '* Исключение'),
            ItemTypeENUM::FUNCTIONAL => Yii::t('global', '* Функциональная'),
            ItemTypeENUM::MINOR => Yii::t('global', '* Незначительнаый'),
            ItemTypeENUM::MAJOR => Yii::t('global', '* Важный'),
            ItemTypeENUM::SETUP => Yii::t('global', '* Ошибка установки'),
            ItemTypeENUM::BLOCK => Yii::t('global', '* Блокирующая ошибка')
        );
    }

    public static function getPriorityList() {
        return array(
            ItemPriorityENUM::MINIMAL => Yii::t('global', '* Низкий'),
            ItemPriorityENUM::NORMAL => Yii::t('global', '* Обычный'),
            ItemPriorityENUM::HIGH => Yii::t('global', '* Важный')
        );
    }

    public static function getStatusList() {
        return array(
            ItemStatusENUM::IS_NEW => Yii::t('global', '* Новый'),
            ItemStatusENUM::IDENTIFIED => Yii::t('global', '* Идентифицирован'),
            ItemStatusENUM::ASSESSED => Yii::t('global', '* В процессе'),
            ItemStatusENUM::RESOLVED => Yii::t('global', '* Решён'),
            ItemStatusENUM::CLOSED => Yii::t('global', '* Закрыт'),
        );
    }

}

?>
