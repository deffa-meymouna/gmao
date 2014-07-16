<?php
return array (
		'controllers' => array (
				'invokables' => array (
						'Utilisateur\Controller\Index' => 'Utilisateur\Controller\IndexController',
						'Utilisateur\Controller\Registration' => 'Utilisateur\Controller\RegistrationController'
				)
		),
		'router' => array (
				'routes' => array (
						'user-index' => array (
								'type' => 'Segment',
								'options' => array (
										'route' => '/user[/:action]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
										),
										'defaults' => array (
												'controller' => 'Utilisateur\Controller\Index',
												'action' => 'index'
										)
								),
								'may_terminate' => true
						),

						'user-register' => array (
								'type' => 'Segment',
								'options' => array (
										'route' => '/user/register[/:action][/:id]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id' => '[a-zA-Z0-9]*'
										),
										'defaults' => array (
												'controller' => 'Utilisateur\Controller\Registration',
												'action' => 'index'
										)
								),
								'may_terminate' => true
						),
						'user-admin' => array (
								'type' => 'Segment',
								'options' => array (
										'route' => '/user/admin[/:action][/:id][/:state]',
										'constraints' => array (
												'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
												'id' => '[0-9]+',
												'state' => '[0-9]'
										),
										'defaults' => array (
												'controller' => 'CsnUser\Controller\Admin',
												'action' => 'index'
										)
								),
								'may_terminate' => true
						)
				)
		),
		'view_manager' => array (
				'template_path_stack' => array (
						'Utilisateur' => __DIR__ . '/../view'
				)
		)
);
