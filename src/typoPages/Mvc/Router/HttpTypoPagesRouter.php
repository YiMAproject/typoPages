<?php
namespace typoPages\Mvc\Router;

use Traversable;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Mvc\Router\Http\RouteInterface;
use Zend\Mvc\Router\Http\RouteMatch;

/**
 * Class HttpTypoPagesRouter
 *
 * @package typoPages\Mvc\Router
 */
class HttpTypoPagesRouter implements RouteInterface
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

        // default options of route, exp. [controller] => 'Index'
        $this->defaults = $options['defaults'];
        unset($options['defaults']);

        $this->params  = $options;
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
        $path = $uri->getPath();

        if ($pathOffset !== null) {
        	if ($pathOffset >= 0 && strlen($path) >= $pathOffset) {
        		if ($this->route !== substr($path, $pathOffset)) {
                    // we are not in defined routes stack (/) | (/content)
        			return false;
        		}
        	}
        }

        d_e($path);

        # get route params
        $reqUri      = $request->getRequestUri();
        if (($qstack = strpos($reqUri, '?')) === false) {
            // we dont have any parameter to match
            return false;
        }

        $queryString = substr($reqUri, $qstack+1);

        $routeParams = array();
        parse_str($queryString, $routeParams);

        /*
         * Route default factory options 
         */
        $params = array_merge($this->defaults, array()/*$this->params*/); // we don't want options as default values
        $params = array_merge($params, $routeParams);

       	return new RouteMatch($params, strlen($this->route));
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
}
