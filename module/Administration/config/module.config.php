<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Administration\Controller\Index' => Administration\Controller\IndexController::class,
            'Administration\Controller\User'  => Administration\Controller\UserController::class,
            'Administration\Controller\Role'  => Administration\Controller\RoleController::class,
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'router' => array(
        'routes' => array(
            'zfcadmin' => array(
                'type' => 'Literal',
                'priority' => 1000,
                'options' => array(
                    'route'    => '/backend',
                    'defaults' => array(
                        'controller' => 'Administration\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
              'may_terminate' => true,
                'child_routes' => array(
                    'user' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/user',
                            'defaults' => array(
                                'controller' => 'Administration\Controller\User',
                                'action'     => 'index',
                            ),
                        ),
                    ),
                    'role' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/role',
                            'defaults' => array(
                                'controller' => 'Administration\Controller\Role',
                                'action'     => 'index',
                            ),
                        ),
                    ),
                    
                ),
            ),
        ),
    ),
);