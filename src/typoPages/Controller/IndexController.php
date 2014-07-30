<?php
namespace typoPages\Controller;

use typoPages\Model\PageEntity;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class IndexController
 *
 * @package typoPages\Controller
 */
class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        // We always has a page because of route detection ...

        /** @var $page PageEntity */
        $page = $this->params()->fromRoute('page');
        // used by "load.ondemand.columns" filter to load extra columns,
        // @see PageTable::getPreparePageEntity
        $page->loadExtraColumnsByFilter = true;

        // Create page widget instance by pageEntity
        $sm = $this->getServiceLocator();
        $pageFactory = $sm->get('typoPages.Page.Factory');
        $pageWidget  = $pageFactory->factory($page);

        $content = $pageWidget->render();

        return array(
            'content' => $content
        );
    }
}