<?php
namespace typoPages;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\ModuleManagerInterface;

/**
 * Class Module
 *
 * @package typoPages
 */
class Module implements
    InitProviderInterface,
    ServiceProviderInterface,
    ConfigProviderInterface,
    AutoloaderProviderInterface
{
    /**
     * Initialize workflow
     *
     * @param  ModuleManagerInterface $manager
     *
     * @return void
     */
    public function init(ModuleManagerInterface $moduleManager)
    {
        /** @var $moduleManager \Zend\ModuleManager\ModuleManager */
        $moduleManager->loadModule('yimaAdminor');

        //$moduleManager->loadModule('yimaStaticUriHelper');
        if (! $moduleManager->getModule('yimaWidgetator')) {
            // yimaWidgetator needed and not loaded.
            // loadModule in default zf2 can't load more than one module
            throw new \Exception(
                'Module "yimaWidgetator" not loaded, by zf2 module manager we can`t load this module automatically.'
                .'please enable this module and put before "typoPages".'
            );
        }
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'typoPages.Model.Page'   => 'typoPages\Model\PageModel',
                'typoPages.Page.Factory' => 'typoPages\Service\PageFactory',
            ),
        );
    }

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }
}
