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
        $serviceList = array(
            'UserService',
            'ProfileService',
            'ProjectService',
            'ItemService',
            'RequestService',
            'SubscribeService',
            'CommentService'
        );
        foreach ($serviceList as $serviceName) {
             $ioc->willUse(new Reused($serviceName));
        }
    }
}

?>
