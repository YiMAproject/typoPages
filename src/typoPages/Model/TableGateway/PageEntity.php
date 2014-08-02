<?php
namespace typoPages\Model\TableGateway;

use typoPages\Model\PageEntity as BasePageEntity;
use Poirot\Dataset;
use yimaBase\Db\TableGateway\AbstractTableGateway;

/**
 * Class PageEntity
 * : Pages Entity can have custom entity fields for each page type
 *
 * @package typoPages\Model\Entity
 */
class PageEntity extends BasePageEntity
{
    /**
     * Injected TableGateway
     *
     * @var AbstractTableGateway $tableGateway
     */
    protected $tableGateway;

    /**
     * Flag to check onDemand loading by filter
     *
     * @var bool
     */
    protected $onDemandLoad = true;

    /**
     * Prepare page for output render (pageFactory)
     *
     * return $this
     */
    public function prepare()
    {
        if (!$this->hasFilter('*', 'load.ondemand.columns')) {
            // Entity Filter to load DMS and Translatable Columns of Pages
            $this->addFilter('*', new Dataset\EntityFilterCallable(array(
                'callable' => array($this->getTableGateway(), 'prepareTableGateway'),
                'name'     => 'load.ondemand.columns',
                'priority' => 10000
            )));
        }

        return $this;
    }

    /**
     * Inject TableGateway
     * @see PageTable::postInit()
     *
     * @param AbstractTableGateway $table
     */
    public function setTableGateway(AbstractTableGateway $table)
    {
        $this->tableGateway = $table;
    }

    /**
     * Get Injected TableGateway
     *
     * @return AbstractTableGateway
     */
    public function getTableGateway()
    {
        return $this->tableGateway;
    }

    /**
     * Set OnDemand Load Flag,
     * true to Load Data and vice versa
     *
     * @param bool $bool
     */
    public function setOnDemandLoad($bool = true)
    {
        $this->onDemandLoad = $bool;
    }

    /**
     * Is Entity Flag To OnDemand Load Data?
     *
     * @return bool
     */
    public function isOnDemandLoad()
    {
        return $this->onDemandLoad;
    }

    /**
     * Implement Entity as ResultSet
     *
     * @param array $data Data
     *
     * @return $this
     */
    public function exchangeArray($data)
    {
        $this->setProperties($data);

        return $this;
    }
}
