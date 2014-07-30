<?php
namespace typoPages\Model;

use typoPages\Model\Interfaces\PageInterface;
use typoPages\Model\TableGateway\PageTable;
use yimaLocali\LocaleAwareInterface;

/**
 * Class PageModel
 *
 * @package typoPages\Model\Entity
 */
class PageModel implements
    PageInterface,
    LocaleAwareInterface
{
    /**
     * @var string Locale Language
     */
    protected $language;

    /**
     * @var PageTable DataBase Table Gateway
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
     * @return PageEntity
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
}
