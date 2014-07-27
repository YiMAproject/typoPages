<?php
namespace typoPages\Model;

use typoPages\Model\Interfaces\PageInterface;

/**
 * Class PageModel
 *
 * @package typoPages\Model\Entity
 */
class PageModel implements PageInterface
{
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
}
