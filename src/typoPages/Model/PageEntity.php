<?php
namespace typoPages\Model;

/**
 * Class PageEntity
 *
 * @package typoPages\Model\Entity
 */
class PageEntity extends \Poirot\Dataset\Entity
{
    protected $properties = array(
        'identity' => null,
        'type'     => null,
    );

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
