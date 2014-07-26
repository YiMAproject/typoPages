<?php
namespace typoPages;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\SimpleRouteStack as HttpSimpleRouteStack;

/**
 * Class Module
 *
 * @package typoPages
 */
class Module implements
    BootstrapListenerInterface,
    ConfigProviderInterface,
    AutoloaderProviderInterface
{
    /**
     * Listen to the bootstrap MvcEvent
     * - setup pages custom router
     *
     * @param EventInterface $e
     *
     * @return array
     */
    public function onBootstrap(EventInterface $e)
    {
        /** @var $e MvcEvent */
        /** @var $r \Zend\Mvc\Router\Http\TreeRouteStack */
        $r = $e->getRouter();
        if (!$r instanceof HttpSimpleRouteStack) {
            throw new \Exception(
                sprintf(
                    'Pages work with "Http\SimpleRouteStack" but current router is instance of "%s".',
                    get_class($r)
                )
            );
        }

        // Add Pages Specific Router From Config into RoutePlugin Manager ----------------------------\

        /** @var $routePluginManager \Zend\Mvc\Router\RoutePluginManager */
        $routePluginManager = $r->getRoutePluginManager();
        if (!$routePluginManager->has('typoPagesRouter') ){

            // there is no pages router, add router ...
            // Set RouteInterface from config {
            $router = null;
            $sm     = $e->getApplication()->getServiceManager();
            $config = $sm->get('config');
            if (isset($config['typopages']) && is_array($config['typopages'])
                && isset($config['typopages']['router'])
            ) {
                $router = $config['typopages']['router'];
            }

            if (!$router) {
                // no router defined
                throw new \Exception('No Router Found in Config for typoPages.');
            }


            // plugin manager will validate router (isValid)
            $routePluginManager->setInvokableClass(
                'typoPagesRouter',
                $router
            );
            // ... }
        }
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
