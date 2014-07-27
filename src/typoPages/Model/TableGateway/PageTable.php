<?php
namespace typoPages\Model\TableGateway;

use yimaBase\Db\TableGateway\AbstractTableGateway;
use yimaBase\Db\TableGateway\Feature\DmsFeature;
use yimaLocalize\Db\TableGateway\Feature\TranslatableFeature;

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

	public function init()
	{
		$feature = new TranslatableFeature(array('title','description','note'));
		$this->featureSet->addFeature($feature);
		
		// put this on last, reason is on pre(Action) manupulate columns rawdataSet
		$feature = new DmsFeature(array('image','url','note'));
		$this->featureSet->addFeature($feature);
	}
}
