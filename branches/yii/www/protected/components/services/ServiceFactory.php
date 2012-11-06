<?php
/**
 * Description of ServiceFactory
 *
 * @author EntityFX
 */
final class ServiceFactory {
    
    private static $_serviceContainer = array();
    
    /**
     * Returns service instance
     */
    public static function getService($serviceName) {
        if (!isset(self::$_serviceContainer[$serviceName])) {
            $className = $serviceName.Service;
            self::$_serviceContainer[$serviceName] = new $className;
        }
        return self::$_serviceContainer[$serviceName];
    }
}

?>
