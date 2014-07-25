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
	'doctrine' => array(
			'configuration' => array(
					'orm_default' => array(
							'generate_proxies' => true,
					),
			),
			'driver' => array(
					'reseau_driver' => array(
							'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
							'cache' => 'array',
							'paths' => array(
								__DIR__ . '/../src/Reseau/Entity',
							),
					),
					'orm_default' => array(
							'drivers' => array(
									'Reseau\Entity' => 'reseau_driver',
							),
					),
			),
	),
);
