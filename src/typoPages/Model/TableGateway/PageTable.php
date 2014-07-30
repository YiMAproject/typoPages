<?php
namespace typoPages\Model\TableGateway;

use Poirot\Dataset;
use typoPages\Model\PageEntity;
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

        // add PageEntity as Row Prototype
        $this->resultSetPrototype
           ->setArrayObjectPrototype(
                $this->getPreparePageEntity()
            );
    }

    /**
     * Get Prepared Page Entity With Filters,
     * For OnDemand Loading Pages Columns
     *
     * @return PageEntity
     */
    protected function getPreparePageEntity()
    {
        $entity = new PageEntity();

        $self = $this;
        if (!$entity->hasFilter('*', 'load.ondemand.columns')) {
            $entity->addFilter('*', new Dataset\EntityFilterCallable(array(
                'callable' => function(Dataset\FilterObjectInterface $fo) use ($self)
                    {
                        // Get current entity fields (props.)
                        $nttClone = clone $fo->getProperty('entity');
                        $nttClone->clearFilters();
                        $nttFields = array_keys($nttClone->getArrayCopy());
                        if (in_array($fo->getProperty('__get'), $nttFields)
                            && !$nttClone->loadExtraColumnsByFilter) {
                            return;
                        }

                        // load page with dms and translatable features

                        /*$feature = new TranslatableFeature(array('title','description','note'));
                        $self->featureSet->addFeature($feature);*/
                        // put this on last, reason is on pre(Action) manupulate columns rawdataSet
                        $feature = new DmsFeature();
                        $self->featureSet->addFeature($feature);
                        $self->featureSet->setTableGateway($self);

                        // load entity data
                        $pkClmn   = $this->getPrimaryKey();
                        $pageID   = $nttClone->{$pkClmn};
                        $loadEntt = $self->select(array($pkClmn => $pageID));
                        $loadEntt = $loadEntt->current();

                        // Exchange new data to Entity
                        $reEntity = $fo->getProperty('entity')
                            ->setProperties($loadEntt->getArrayCopy());
                        $reEntity->loadExtraColumnsByFilter = false; // page is loaded

                        // Return new value to this call
                        $get = $fo->getProperty('__get');
                        $fo->setValue($reEntity->get($get));
                    },
                'name'     => 'load.ondemand.columns',
                'priority' => 10000
            )));
        }

        return $entity;
    }
}
