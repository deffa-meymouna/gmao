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
                    'route' => '/reseau[/:action][/:reseau][/:confirmation]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    	'reseau' => '[0-9]*',
                    	'confirmation' => '[0-2]',
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
	'view_helpers' => array(
			'invokables' => array(
					'ip' => 'Reseau\View\Helper\Ip',
			),
	),
	'view_helper_config' => array(
			'flashmessenger' => array(
					'message_open_format'      => '<div%s><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><ul><li>',
					'message_close_string'     => '</li></ul></div>',
					'message_separator_string' => '</li><li>'
			)
	),
	'service_manager' => array(
		'factories' =>	array(
			'ReseauService'	=> 'Reseau\Service\Factory\ReseauService',
			'IpService'		=> 'Reseau\Service\Factory\IpService',
		),
		'invokables' => array(
			'reseauForm'   => 'Reseau\Form\ReseauForm',
			'reseauFilter' => 'Reseau\Form\ReseauFilter',
			'reservationIpForm'   => 'Reseau\Form\ReservationIpForm',
			'reservationIpFilter' => 'Reseau\Form\ReservationIpFilter',
		)
	),


);
