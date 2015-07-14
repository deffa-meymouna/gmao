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
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/user[/page:page][/itemsPerPage:itemsPerPage][/sortBy:sort][?search=:search]',
                            'defaults' => array(
                                'controller'   => 'Administration\Controller\User',
                                'action'       => 'index',
                                'page'         => 1,                                
                                'itemsPerPage' => 10,
                                'sort'         => 'IdAsc',
                                'search'       => '',
                            ),
                            'constraints' => array(
                                'page'         => '\d+',
                                'itemsPerPage' => '\d+',
                                'sort'         => '(IdAsc|IdDesc|UsernameAsc|UsernameDesc|EmailAsc|EmailDesc|DisplayNameAsc|DisplayNameDesc|InscriptionAsc|InscriptionDesc|LastVisiteAsc|LastVisiteDesc)', // only login, logout or info are allowed
                            ),
                        ),
                    ),
                    'user-action' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/user/:action/:user',
                            'defaults' => array(
                                'controller'   => 'Administration\Controller\User',
                            ),
                            'constraints' => array(
                                'user'         => '[0-9]+',
                                'action'       => '[a-zA-Z][a-zA-Z0-9_-]+',
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