<?php
namespace typoPages\Model\TableGateway;

use Poirot\Dataset;
use yimaBase\Db\TableGateway\AbstractTableGateway;
use yimaBase\Db\TableGateway\Feature\DmsFeature;
use Zend\Db\ResultSet\ResultSet;

/**
 * Class PageTable
 *
 * @package typoPages\Model\TableGateway
 */
class PageTable extends AbstractTableGateway
{
	# db table name
    protected $table = 'typopages_page';

	// this way you speed up running by avoiding metadata call to reach primary key
	// exp. usage in Translation Feature
	protected $primaryKey = 'page_id';

    /**
     * Post Initialize Table
     *
     */
    public function postInit()
    {
        if (!$this->resultSetPrototype instanceof ResultSet) {
            // this table work with ResultSet
            $this->resultSetPrototype = new ResultSet;
        }

        // add PageEntity as Row Result Prototype
        $entity = PageEntity::factory(array('table_gateway' => $this));
        $this->resultSetPrototype
           ->setArrayObjectPrototype($entity);
    }

    /**
     * Prepare TableGateway called by filter on PageEntity::Prepare
     *
     * @param Dataset\FilterObjectInterface $fo
     */
    public function prepareTableGateway(Dataset\FilterObjectInterface $fo)
    {
        // Get current entity fields (props.)
        /** @var $nttClone PageEntity */
        $nttClone = clone $fo->getProperty('entity');
        $nttClone->clearFilters();
        $nttFields = array_keys($nttClone->getArrayCopy());
        if (in_array($fo->getProperty('__get'), $nttFields)
            && !$nttClone->isOnDemandLoad()) {
            return;
        }

        // load page with dms and translatable features
        $defaultFeatureSet = clone $this->featureSet;
        /*$feature = new TranslatableFeature(array('title','description','note'));
        $this->featureSet->addFeature($feature);*/
        // put this on last, reason is on pre(Action) manupulate columns rawdataSet
        $feature = new DmsFeature();
        $this->featureSet->addFeature($feature);
        $this->featureSet->setTableGateway($this);

        // load entity data
        $pkClmn   = $this->getPrimaryKey();
        $pageID   = $nttClone->{$pkClmn};
        $loadEntt = $this->select(array($pkClmn => $pageID));
        $loadEntt = $loadEntt->current();

        // restore default featureSet
        $this->featureSet = $defaultFeatureSet;

        // Exchange new data to Entity
        /** @var $reEntity PageEntity */
        $reEntity = $fo->getProperty('entity')
            ->merge($loadEntt->getArrayCopy());
        $reEntity->setOnDemandLoad(false); // page is loaded

        // Return new value to this call
        $get = $fo->getProperty('__get');
        $fo->setValue($reEntity->get($get));
    }
}
