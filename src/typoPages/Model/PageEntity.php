<?php
namespace typoPages\Model;

/**
 * Class PageEntity
 * : Pages Entity can have custom entity fields for each page type
 *
 * @package typoPages\Model\Entity
 */
class PageEntity extends \Poirot\Dataset\Entity
{
    protected $properties = array(
        'identity'    => null, /* Page Identity */
        'type'        => null, /* Page Type */
        'parent_page' => null, /* Parent Page */
    );

    protected $strictMode = false;

    /**
     * Proxy Url to Identity
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->identity;
    }
}
