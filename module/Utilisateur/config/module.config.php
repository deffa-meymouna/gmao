<?php
return array (
		'controllers' => array (
				'invokables' => array (
						'Utilisateur\Controller\Index' => 'Utilisateur\Controller\IndexController' 
				) 
		),
		'router' => array (
				'routes' => array (
						'login' => array (
								'type' => 'Literal',
								'options' => array (
										'route' => '/login',
										'defaults' => array (
												'__NAMESPACE__' => 'Utilisateur\Controller',
												'controller' => 'Index',
												'action' => 'login' 
										) 
								),
								'may_terminate' => true,
								'child_routes' => array (
										'default' => array (
												'type' => 'Segment',
												'options' => array (
														'route' => '/[...]',
														'defaults' => array (
																'__NAMESPACE__' => 'Utilisateur\Controller',
																'controller' => 'Index',
																'action' => 'login' 
														) 
												) 
										) 
								) 
						),
						'utilisateur' => array (
								'type' => 'Literal',
								'options' => array (
										'route' => '/utilisateur',
										'defaults' => array (
												// Change this value to reflect the namespace in which
												// the controllers for your module are found
												'__NAMESPACE__' => 'Utilisateur\Controller',
												'controller' => 'Index',
												'action' => 'index' 
										) 
								),
								'may_terminate' => true,
								'child_routes' => array (
										// This route is a sane default when developing a module;
										// as you solidify the routes for your module, however,
										// you may want to remove it and replace it with more
										// specific routes.
										'default' => array (
												'type' => 'Segment',
												'options' => array (
														'route' => '/[:controller[/:action]]',
														'constraints' => array (
																'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
																'action' => '[a-zA-Z][a-zA-Z0-9_-]*' 
														),
														'defaults' => array () 
												) 
										) 
								) 
						) 
				) 
		),
		'view_manager' => array (
				'template_path_stack' => array (
						'Utilisateur' => __DIR__ . '/../view' 
				) 
		) 
);
