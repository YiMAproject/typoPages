<?php
namespace typoPages\Service;

use typoPages\Model\PageEntity;
use yimaWidgetator\Service\WidgetManager;
use yimaWidgetator\Widget\WidgetInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class PageFactory
 *
 * @package typoPages\Service
 */
class PageFactory implements ServiceLocatorAwareInterface
{
    /**
     * @var \Zend\ServiceManager\ServiceManager ServiceLocator
     */
    protected $serviceLocator;

    /**
     * @var WidgetManager
     */
    protected $widgetManager;

    /**
     * Get Widget by pageEntity
     *
     * @param PageEntity $page
     *
     * @return WidgetInterface
     */
    public function factory(PageEntity $page)
    {
        $page->prepare();

        $options = $page->getArrayCopy();

        $widget = $options['type'];
        unset($options['type']);

        return $this->getWidgetManager()->get($widget, $options);
    }

    /**
     * Get Widget Manager
     *
     * @return WidgetManager
     */
    protected function getWidgetManager()
    {
        if (! $this->widgetManager) {
            $serviceManager = $this->getServiceLocator();
            $widgetManager  = $serviceManager->get('yimaWidgetator.WidgetManager');

            if (!($widgetManager instanceof WidgetManager) || !($widgetManager instanceof AbstractPluginManager)) {
                throw new \Exception(
                    sprintf(
                        'WidgetManager must instance of WidgetManager or AbstractPluginManager, but "%s" given from \'yimaWidgetator.WidgetManager\'',
                        is_object($widgetManager) ? get_class($widgetManager) : gettype($widgetManager)
                    )
                );
            }

            $this->widgetManager = $widgetManager;
        }

        return $this->widgetManager;
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * Get service locator
     *
     * @return \Zend\ServiceManager\ServiceManager
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}