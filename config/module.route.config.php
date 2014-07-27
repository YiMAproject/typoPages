<?php
return array(
    'routes' => array(
        __NAMESPACE__ => array(
            'type'    => 'typoPages\Mvc\Router\HttpTypoPagesRouter', // use class exists
            'options' => array(
                'route'    => '/',
                'suffix'   => 'html',
            ),
            'may_terminate' => false,
        ),
    ),
);
