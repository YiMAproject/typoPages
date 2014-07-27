<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'typoPages\Controller\IndexController' => 'typoPages\Controller\IndexController'
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'router'     => include_once 'module.route.config.php',
);
