<?php
/**
 * Navigation Global Configuration Override
 *
 */
return array (
		'navigation' => array (
				'default' => array (
						array (
								'icon'	=> 'home',
								'label' => 'Home',
								'route' => 'home' 
						),
						array (
								'icon'	=> 'road',
								'label' => 'Présentation',
								'route' => 'application',
								'pages' => array (
										array (
												'icon'	=> 'eye',
												'label' => 'Découvrir les fonctionnalités',
												'title' => 'Découvrir les fonctionnalités de l\'application GMAO',
												'route' => 'application',
												'action' => 'fonction' 
										),
										array (
												'icon'	=> 'globe',
												'label' => 'Découvrir les services',
												'title' => 'Découvrir les services de AT-IT',
												'route' => 'application',
												'action' => 'service' 
										),
										array (
												'icon'	=> 'code',
												'label' => 'Suivre les évolutions',
												'title' => 'Se connecter au portail de développement pour suivre les évolutions de GMAO',
												'route' => 'application',
												'action' => 'suivre' 
										),
										array (
												'icon'	=> 'phone',
												'label' => 'Contactez nous !',
												'title' => 'Accéder au formulaire de contact',
												'route' => 'application',
												'action' => 'contact'
										),
																				
								) 
						),
						/*array(
								'label' => 'Page #1',
								'route' => 'page-1',
								'pages' => array(
										array(
												'label' => 'Child #1',
												'route' => 'page-1-child',
										),
								),
						),
						array(
								'label' => 'Page #2',
								'route' => 'page-2',
						),*/
				) 
		) 
);