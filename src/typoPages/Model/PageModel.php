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
class PageModel extends PageTable implements
    PageInterface,
    LocaleAwareInterface
{
    /**
     * @var string Locale Language
     */
    protected $language;

    /**
     * Get Page Object By Identity
     *
     * @param $identity
     *
     * @return PageEntity
     */
    public function getPageByIdentity($identity)
    {
        if ($identity != '/news/new_world_order') {
            return false;
        }

        return new PageEntity(array(
            'identity' => $identity,
            'type'     => 'simple',
        ));
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
