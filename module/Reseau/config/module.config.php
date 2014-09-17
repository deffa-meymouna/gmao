<?php
return array (
		'controllers' => array (
				'invokables' => array (
						'Reseau\Controller\Index' => 'Reseau\Controller\IndexController',
						'Reseau\Controller\Machine' => 'Reseau\Controller\MachineController'
				)
		),
		'router' => array (
				'routes' => array (
						'reseau' => array (
								'type' => 'Segment',
								'options' => array (
										'route' => '/reseau[/:action][/:reseau][/:ip][/:confirmation]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'reseau' => '[0-9]*',
												'ip' => '[0-9]*',
												'confirmation' => '[0-2]'
										),
										'defaults' => array (
												'controller' => 'Reseau\Controller\Index',
												'action' => 'index'
										)
								),
								'may_terminate' => true
						),
						'machine' => array (
								'type' => 'Segment',
								'options' => array (
										'route' => '/machine[/:action][/:machine][/reseau[/:reseau]][/ip[/:ip]][/confirmation[/:confirmation]]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'machine' => '[0-9]*',
												'reseau' => '[0-9]*',
												'ip' => '[0-9]*',
												'confirmation' => '[0-2]'
										),
										'defaults' => array (
												'controller' => 'Reseau\Controller\Machine',
												'action' => 'index'
										)
								),
								'may_terminate' => true
						)
				)
		),
		'view_manager' => array (
				'template_path_stack' => array (
						'Reseau' => __DIR__ . '/../view'
				)
		),
		'doctrine' => array (
				'configuration' => array (
						'orm_default' => array (
								'generate_proxies' => true
						)
				),
				'driver' => array (
						'reseau_driver' => array (
								'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
								'cache' => 'array',
								'paths' => array (
										__DIR__ . '/../src/Reseau/Entity'
								)
						),
						'orm_default' => array (
								'drivers' => array (
										'Reseau\Entity' => 'reseau_driver'
								)
						)
				)
		),
		'view_helpers' => array (
				'invokables' => array (
						'ip' => 'Reseau\View\Helper\Ip'
				)
		),
		'view_helper_config' => array (
				'flashmessenger' => array (
						'message_open_format' => '<div%s><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><ul><li>',
						'message_close_string' => '</li></ul></div>',
						'message_separator_string' => '</li><li>'
				)
		),
		'service_manager' => array (
				'factories' => array (
						'IpService' => 'Reseau\Service\Factory\IpService',
						'MachineService' => 'Reseau\Service\Factory\MachineService',
						'ReseauService' => 'Reseau\Service\Factory\ReseauService'
				),
				'invokables' => array (
						'IpPourMachineForm' => 'Reseau\Form\IpPourMachineForm',
						'IpPourMachineFilter' => 'Reseau\Form\IpPourMachineFilter',
						'reseauForm' => 'Reseau\Form\ReseauForm',
						'machineForm' => 'Reseau\Form\MachineForm',
						'reseauFilter' => 'Reseau\Form\ReseauFilter',
						'reservationIpForm' => 'Reseau\Form\ReservationIpForm',
						'reservationIpFilter' => 'Reseau\Form\ReservationIpFilter',
						'referencementIpForm' => 'Reseau\Form\ReferencementIpForm',
						'referencementIpFilter' => 'Reseau\Form\ReferencementIpFilter'
				)
		)
)
;
