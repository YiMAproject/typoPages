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
        'page_id'     => null, /* Page Identity */
        'url'         => null, /* Page Url*/
        'type'        => null, /* Page Type */
        'parent_page' => null, /* Parent Page */
    );

    /**
     * We want to add costume fields runtime for pages
     *
     * @var bool Strict Mode
     */
    protected $strictMode = false;

    /**
     * Prepare page for output render (pageFactory)
     *
     * return $this
     */
    public function prepare(){ }
}
