<?php
namespace typoPages\Model\Interfaces;

use typoPages\Model\PageEntity;

/**
 * Interface PagesInterface
 *
 * @package typoPages\Model
 */
interface PageInterface
{
    /**
     * Set Locale (language)
     * : used for translation content
     *
     * @param $locale
     *
     * @return static
     */
    public function setLocale($locale);

    /**
     * Get Locale (Language)
     *
     * @return string
     */
    public function getLocale();

    /**
     * Get Page Object By Identity
     *
     * @param $identity
     *
     * @return PageEntity
     */
    public function getPageByIdentity($identity);
}
