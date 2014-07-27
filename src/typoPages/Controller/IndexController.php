<?php
namespace typoPages\Controller;

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
        // We always has a page because of route detection
        $page = $this->params()->fromRoute('page');

        d_e($page);
    }
}