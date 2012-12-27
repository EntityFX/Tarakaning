<?php

/**
 * Get Ioc container and set parameters for him
 *
 * @author EntityFX
 */
final class Ioc {

    /**
     * Phemto ioc container
     * 
     * @var Phemto 
     */
    private static $_instance;

    /**
     *
     * @return Phemto 
     */
    public static function create() {
        if (self::$_instance == null) {
            self::$_instance = new Phemto();
            self::initIoc();
        }
        return self::$_instance;
    }

    private static function initIoc() {
        $ioc = self::$_instance;
        $singletonServiceList = array(
            'UserService',
            'ProfileService',
            'ProjectService',
            'ItemService',
            'RequestService',
            'SubscribeService',
            'CommentService'
        );
        foreach ($singletonServiceList as $service) {
            $ioc->willUse(new Reused($service));
        }
        
        $ioc->whenCreating('ItemService')
            ->forVariable('projectId')
            ->willUse(new Value(Yii::app()->user->defaultProjectId))
            ->forVariable('userId')
            ->willUse(new Value(Yii::app()->user->id));
        
    }

}

?>
