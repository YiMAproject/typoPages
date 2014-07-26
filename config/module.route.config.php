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
                // note: We have to use custom route as child because
                //       on router factory typoPagesRouter must exists in plugin manager
                //       that with ZF default behave it's not possible.
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
