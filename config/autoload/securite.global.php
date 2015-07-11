<?php

return [
    'bjyauthorize' => [

        // Using the authentication identity provider, which basically reads the roles from the auth service's identity
        'identity_provider' => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',
        //Les roles viennent d'une base de données (à voir)
        'role_providers'        => array(
            // using an object repository (entity repository) to load all roles into our ACL
            'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => array(
                'object_manager'    => 'doctrine.entitymanager.orm_default',
                'role_entity_class' => 'Application\Entity\Role',
            ),
        ),
    
        //Les ressources viennent de ce fichier
        'resource_providers' => [
            \BjyAuthorize\Provider\Resource\Config::class => [
                'homePage'       => [],
                'lockPage'       => [],
                'examples'       => [],
                'administration' => [],
                'userAdmin'      => [],
                'roleAdmin'      => [],
            ],
        ],
        //Je place les droits de l'application blanche ici
        //Les règles d'accès sont les suivantes :
        'rule_providers' => [
            \BjyAuthorize\Provider\Rule\Config::class => [
                'allow' => [
                    //[users], [resources], [privileges]
                    [['guest','user','admin'],['homePage','examples'],'view'],
                    ['admin','administration','list'],
                    ['admin',['userAdmin','roleAdmin'],['add','view','list','edit','delete']],
                    
                ],
            ],
         ],
        //Activation de droits selon le controller
        'guards' => [
            \BjyAuthorize\Guard\Controller::class => [
                //Gardes pour zftool
                ['controller' => 'ZFTool\Controller\Classmap', 'roles' => []],
                ['controller' => 'ZFTool\Controller\Config', 'roles' => []],
                ['controller' => 'ZFTool\Controller\Create', 'roles' => []],
                ['controller' => 'ZFTool\Controller\Diagnostics', 'roles' => []],
                ['controller' => 'ZFTool\Controller\Info', 'roles' => []],
                ['controller' => 'ZFTool\Controller\Install', 'roles' => []],
                ['controller' => 'ZFTool\Controller\Module', 'roles' => []],
                //Gardes pour zfcuser
                ['controller' => 'zfcuser', 'roles' => []],
                //Gardes pour zfcadmin
                ['controller' => 'ZfcAdmin\Controller\AdminController', 'roles' => ['admin']],
                ['controller' => 'Administration\Controller\Index', 'roles' => ['admin']],
                ['controller' => 'Administration\Controller\User',  'roles' => ['admin']],
                ['controller' => 'Administration\Controller\Role',  'roles' => ['admin']],
                //Gardes pour le contrôleur Index
                [
                    'controller' => ['Application\Controller\Index'],
                    'roles' => ['guest'] ,
                    'action' => ['contact','exemple1','exemple2','index','sitemap','suivre']
                ],
    
                //['controller' => ['Application\Controller\AdminController'], 'roles' => ['admin']],
                //['controller' => ['Module\Controller\Menu2Controller'], 'roles' => ['admin','affiliate']],
                //['controller' => ['Module\Controller\Menu3Controller'], 'roles' => ['admin','affiliate','guest']],
            ],
        ],
    ],
];