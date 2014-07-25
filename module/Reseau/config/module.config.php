<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Reseau\Controller\Index' => 'Reseau\Controller\IndexController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'reseau' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/reseau[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Reseau\Controller\Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Reseau' => __DIR__ . '/../view',
        ),
    ),
);
