<?php
return array(
    /**
     * Register Widgets in WidgetManager
     * - This widgets used by typoPages as Page types
     *
     * each widget must instance of WidgetInterface
     */
    'yima_widgetator' => array(
        // This is configurable service manager config
        'invokables' => array(
            'simple' => 'typoPages\Pages\Simple',
        ),
    ),

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
