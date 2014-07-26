<?php
return array(
    'typopages' => array(
        # invokable class as a service for route plugin manager
        'router' => 'typoPages\Mvc\Router\HttpTypoPagesRouter',
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'router'     => include_once 'module.route.config.php',
);
