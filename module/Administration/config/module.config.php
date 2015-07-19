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
    'view_helper_config' => array(
        'flashmessenger' => array(
            'message_open_format'      => '<div%s><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><ul><li>',
            'message_close_string'     => '</li></ul></div>',
            'message_separator_string' => '</li><li>'
        )
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
                            'route' => '/user[/action-:action][/user:user][/confirmation:confirmation][/page:page][/itemsPerPage:itemsPerPage][/sortBy:sort][?search=:search]',
                            'defaults' => array(
                                'controller'   => 'Administration\Controller\User',
                                'page'         => 1,
                                //It's now an option defined in ModuleOptions via administration.global.php                              
                                //'itemsPerPage' => 10,
                                'sort'         => 'IdAsc',
                                'search'       => '',
                                'action'       => 'list',
                                'user'         => 0,                                
                                'confirmation' => 0,
                            ),
                            'constraints' => array(
                                'user'         => '[0-9]+',
                                'action'       => '[a-zA-Z][a-zA-Z_-]+',
                                'confirmation' => '[0-2]',
                                'page'         => '[0-9]+',
                                'itemsPerPage' => '[0-9]+',
                                'sort'         => '(IdAsc|IdDesc|UsernameAsc|UsernameDesc|EmailAsc|EmailDesc|DisplayNameAsc|DisplayNameDesc|InscriptionAsc|InscriptionDesc|LastVisiteAsc|LastVisiteDesc)', // only login, logout or info are allowed
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