<?php
namespace typoPages\Model\TableGateway;

use Poirot\Dataset;
use Poirot\Dataset\FilterObjectInterface;
use typoPages\Service\PageFactory;
use yimaBase\Db\TableGateway\AbstractTableGateway;
use yimaBase\Db\TableGateway\Feature\DmsFeature;
use yimaLocalize\Db\TableGateway\Feature\TranslatableFeature;
use Zend\Db\ResultSet\ResultSet;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

/**
 * Class PageTable
 *
 * @package typoPages\Model\TableGateway
 */
class PageTable extends AbstractTableGateway
    implements ServiceManagerAwareInterface
{
	# db table name
    protected $table = 'typopages_page';

	// this way you speed up running by avoiding metadata call to reach primary key
	// exp. usage in Translation Feature
	protected $primaryKey = 'page_id';

    /**
     * @var ServiceManager
     */
    protected $sm;

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
     * @param FilterObjectInterface $fo
     */
    public function prepareTableGateway(FilterObjectInterface $fo)
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

        // load page with dms and translatable features ... {
        $pageType    = $nttClone->get('type');
        /** @var $pageFactory PageFactory */
        $pageFactory = $this->sm->get('typoPages.Page.Factory');
        $pageWidget  = $pageFactory->getPageInstance($pageType);

        $defaultFeatureSet = clone $this->featureSet;
        $feature = new TranslatableFeature($pageWidget->getTranslatableColumns());
        $this->featureSet->addFeature($feature);

        // put this on last, reason is on pre(Action) manipulate columns raw dataSet
        $feature = new DmsFeature($pageWidget->getColumns());
        $this->featureSet->addFeature($feature);
        $this->featureSet->setTableGateway($this);
        // ... }

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

    /**
     * Set service manager
     *
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->sm = $serviceManager;
    }
}
