<?php
return array(
    'routes' => array(
        __NAMESPACE__ => array(
            'type'    => 'typoPages\Mvc\Router\HttpTypoPagesRouter', // use class exists
            'options' => array(
                'route'    => '/c',
            ),
            'may_terminate' => false,
        ),
    ),
);
