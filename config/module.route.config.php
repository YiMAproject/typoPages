<?php
return array(
    'routes' => array(
        __NAMESPACE__ => array(
            'type'    => 'Literal',
            'options' => array(
                'route'    => '/',
                'defaults' => array(
                    '__NAMESPACE__' => __NAMESPACE__,
                    'controller'    => 'Pages',
                    'action'        => 'index',
                ),
            ),
            'may_terminate' => false,
            'child_routes' => array(
                'default' => array(
                    'type'    => 'typoPagesRouter',
                    'options' => array(
                        'route'    => '',
                    ),
                    'may_terminate' => false,
                ),
            ),
        ),
    ),
);
