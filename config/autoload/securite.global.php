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
                'user'           => [],
                'role'           => [],
            ],
        ],
        //Je place les droits de l'application blanche ici
        //Les règles d'accès sont les suivantes :
        'rule_providers' => [
            \BjyAuthorize\Provider\Rule\Config::class => [
                'allow' => [
                    //[users], [resources], [privileges]
                    [['guest','user','admin'],['homePage','examples'],'view'],
                    [['guest','user','admin'],'user','search'],
                    //En dehors de l'admin, on n'a pas le droit d'activer,
                    //sauf si on s'activait soi-même
                    //Mais on ne peut pas être identifié pour s'activer
                    //C'est pour cela que Guest à le droit d'activer
                    ['guest','user','activating'],                    
                    ['admin','administration','list'],
                    ['admin','user',['activating','add','ban','create','edit','delete','list','unban','view']],
                    ['admin','role',['add','create','delete','edit','list','view']],
                    
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