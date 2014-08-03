<?php
namespace typoPages\Model;

use typoPages\Model\Interfaces\PageInterface;
use typoPages\Model\TableGateway\PageEntity as GatewayPageEntity;
use typoPages\Model\TableGateway\PageTable;
use yimaLocali\LocaleAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

/**
 * Class PageModel
 *
 * @package typoPages\Model\Entity
 */
class PageModel implements
    PageInterface,
    LocaleAwareInterface,
    ServiceManagerAwareInterface
{
    /**
     * @var string Locale Language
     */
    protected $language;

    /**
     * @var GatewayPageEntity Table Gateway
     */
    protected $tableGateway;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->tableGateway = new PageTable();
    }

    /**
     * Get a Page By Url
     *
     * @param $url
     *
     * @return GatewayPageEntity
     */
    public function getPageByUrl($url)
    {
        $result = $this->tableGateway->select(array('url' => $url));
        if ($result->count()) {
            $r = $result->current();

            return $r;
        }

        return false;
    }

    /**
     * Set Locale
     *
     * @param $locale
     *
     * @return static
     */
    public function setLocale($locale)
    {
        $this->language = (string) $locale;
    }

    /**
     * Get Locale (Language)
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->language;
    }

    /**
     * Set service manager
     *
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->tableGateway->setServiceManager($serviceManager);
    }
}
