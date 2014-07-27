<?php
namespace typoPages\Mvc\Router;

use Traversable;
use typoPages\Model\Interfaces\PageInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Mvc\Router\Http\RouteInterface;
use Zend\Mvc\Router\Http\RouteMatch;

/**
 * Class HttpTypoPagesRouter
 *
 * @package typoPages\Mvc\Router
 */
class HttpTypoPagesRouter implements
    RouteInterface,
    ServiceLocatorAwareInterface
{
    /**
     * @var int Maximum Priority for Admin Detection Route
     */
    protected $priority = 10000;

	/**
	 * RouteInterface to match.
     * exp. /browse/
	 *
	 * @var string
	 */
	protected $route;
	
    /**
     * Default values.
     *
     * @var array
     */
    protected $defaults;

    /**
     * Any optional Route options that may have used
     *
     * @var array
     */
    protected $params;

    /**
     * @var array
     */
    protected $assembledParams;

    /**
     * @var string Pages Suffix
     */
    protected $suffix;

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * Construct
     *
     * @param $route
     * @param array $options
     */
    public function __construct($route, array $options = array())
    {
    	$this->route    = $route;

        /* 'child_routes' => array(
        'default' => array(
            ...
            'options' => array(
                'route' => '/',
                'defaults' 	 => array(
                    'controller' => 'Index',
                    ...
                ),
            ),
        ),
        */

        // run setter methods
        foreach($options as $option => $value) {
            $method = 'set'.str_replace(' ', '', ucwords(strtolower(str_replace('_', ' ', $option))));
            if (method_exists($this, $method)) {
                $this->{$method}($value);
                unset($options[$option]);
            }
        }

        // default options of route, exp. [controller] => 'Index'
        $this->defaults = $options['defaults'];
        unset($options['defaults']);

        $this->params  = $options;
    }

    /**
     * Set Pages Suffix
     * - http://....../pages.suffix
     *
     * @param $suffix
     */
    protected function setSuffix($suffix)
    {
        $this->suffix = (string) $suffix;
    }

    /**
     * Create a new route with given options.
     * factory(): defined by RouteInterface interface.
     *
     * @param  array|\Traversable $options
     *
     * @throws \Exception
     */
    public static function factory($options = array())
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        } elseif (!is_array($options)) {
            throw new \Exception(__METHOD__ . ' expects an array or Traversable set of options');
        }
        
        if (!isset($options['route']) || empty($options['route'])) {
        	$options['route'] = '/'; 
        }
        
        $route = (string) $options['route'];
        unset($options['route']);

        if (!isset($options['defaults'])) {
            $options['defaults'] = array();
        }

        return new static($route, $options);
    }

    /**
     * match(): defined by RouteInterface interface.
     *
     * @see    Route::match()
     * @param  Request  $request
     * @param  int|null $pathOffset
     * @return RouteMatch|null
     */
    public function match(Request $request, $pathOffset = null)
    {
        if (!method_exists($request, 'getQuery') && !method_exists($request, 'getUri')) {
            return false;
        }

        /** @var $uri \Zend\Uri\Http */
        /** @var $request \Zend\Http\PhpEnvironment\Request */
        $uri  = $request->getUri();
        $path = substr($uri->getPath(), $pathOffset);

        $pageIdentity = $path;
        if (substr($path, -strlen($this->suffix)) === $this->suffix) {
            // we have a uri that end with suffix
            $pageIdentity = substr($path, 0, strlen($path)-(strlen($this->suffix)+1/* 1for. */));
        }

        // get page by url ... {
        $sm = $this->getServiceManager();
        $pagesModel = $sm->get('typoPages.Model.Page');
        if (! $pagesModel instanceof PageInterface) {
            throw new \Exception(
                sprintf(
                    'Pages Model must instanceof PagesInterface but "%s" given.'
                    , is_object($pagesModel) ? get_class($pagesModel) : gettype($pagesModel)
                )
            );
        }

        $page = $pagesModel->getPageByIdentity($pageIdentity);
        if (!$page) {
            return false;
        }
        // ... }

        $this->params['page'] = $page;

        /*
         * Route default factory options 
         */
        $params = array_merge($this->defaults, $this->params);

       	return new RouteMatch($params, strlen($path));
    }

    /**
     * assemble(): Defined by RouteInterface interface.
     *
     * @see    Route::assemble()
     * @param  array $params
     * @param  array $options
     * @return mixed
     */
    public function assemble(array $params = array(), array $options = array())
    {
    	$this->assembledParams = $params;
    	
    	$query = http_build_query($params);

        return $this->route.'?';
    }

    /**
     * Get a list of parameters used while assembling.
     *
     * @return array
     */
    public function getAssembledParams()
    {
        return $this->assembledParams;
    }

    /**
     * Get Service Manager
     *
     * @return \Zend\ServiceManager\ServiceManager
     */
    public function getServiceManager()
    {
        return $this->getServiceLocator()->getServiceLocator();
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}
