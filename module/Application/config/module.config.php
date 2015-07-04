<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/main[/:action]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/main[/:action]',
                            'constraints' => array(
                                'controller' => 'Index',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
    'doctrine' => array (
        'driver' => array (
            'zfcuser_entity' => array (
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => array (
                    __DIR__ . '/../src/Application/Entity'
                )
            ),
            'orm_default' => array (
                'drivers' => array (
                    'Application\Entity' => 'zfcuser_entity',
                )
            )
        )
    ),
    'zfcuser' => array(
        // telling ZfcUser to use our own class
        'user_entity_class'       => 'Application\Entity\User',
        // telling ZfcUserDoctrineORM to skip the entities it defines
        'enable_default_entities' => false,
    ),
    'bjyauthorize' => array(
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
                'homePage' => [],
                'lockPage' => [],
                'exemples' => [],
            ],
        ],
        //Je place les droits de l'application blanche ici
        //Les règles d'accès sont les suivantes :
        'rule_providers' => array(
            \BjyAuthorize\Provider\Rule\Config::class => [
                'allow' => [
                    ['guest',['homePage','exemples'],'view'],
                ],
            ],
        ),
        //Activation de droits selon le controller
        'guards' => [
            \BjyAuthorize\Guard\Controller::class => [
                //Gardes pour zfcuser
                ['controller' => 'zfcuser', 'roles' => []],
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
    ),
);
