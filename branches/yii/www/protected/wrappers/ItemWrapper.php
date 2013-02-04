<?php

/**
 * Description of ItemWrapper
 *
 * @author EntityFX
 */
class ItemWrapper {

    public static function getUserItemListData(array $itemsList) {
        foreach ($itemsList as $item) {
            $itemsTypeList = ItemDataHelper::getItemsTypeList();
            $statusList = ItemDataHelper::getStatusList();
            $priorityList = ItemDataHelper::getPriorityList();
            $res[]= array_merge(
                $item, 
                array(
                    'KindText' => $itemsTypeList[$item[ItemFieldsENUM::KIND]],
                    'StatusText' => $statusList[$item[ItemFieldsENUM::STATUS]],
                    'PriorityText' => $priorityList[$item[ItemFieldsENUM::PRIORITY]]
                )
            );
        }
        return $res;
    }

}
