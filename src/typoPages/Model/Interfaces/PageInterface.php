<?php
namespace typoPages\Model\Interfaces;

use typoPages\Model\Entity\PageEntity;

/**
 * Interface PagesInterface
 *
 * @package typoPages\Model
 */
interface PageInterface
{
    /**
     * Get Page Object By Identity
     *
     * @param $identity
     *
     * @return PageEntity
     */
    public function getPageByIdentity($identity);
}
