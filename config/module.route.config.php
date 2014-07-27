<?php
return array(
    'routes' => array(
        __NAMESPACE__ => array(
            'type'    => 'typoPages\Mvc\Router\HttpTypoPagesRouter', // use class exists
            'options' => array(
                'route'    => '/',
                'suffix'   => 'html',
                'defaults' => array(
                    'controller' => 'typoPages\Controller\IndexController',
                    'action'     => 'index',
                ),
            ),
            'may_terminate' => false,
        ),
    ),
);
