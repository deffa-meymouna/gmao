<?php
/**
 * Navigation Global Configuration Override
 *
 *	pages model
 		'icon'	=> 'home', // Font Awesome icon
		'label' => 'Home', // Label text
		'title' => 'home', //Title attribute for links
		'route' => 'home'  //route map
		'changefreq' => '' //For Sitemap, valid values are always hourly daily weekly monthly yearly never  
		'lastmod' => '' //For Sitemap, valid values are W3C Date or YYYY-MM-DD
		'priority' => '1.0' //The value should be a decimal between 0.0 and 1.0  
 */
return array (
		'navigation' => array (
				'default' => array (
						array (
								'icon'	=> 'home',
								'label' => 'Home',
								'title' => 'GMAO',
								'route' => 'home',
								'priority' => 1,
								'changefreq' => 'weekly',
						),
						array (
								'icon'	=> 'road',
								'label' => 'Présentation',
								'route' => 'application',
								'priority' => 0.8,
								'changefreq' => 'weekly',
								'pages' => array (
										array (
												'icon'	=> 'eye',
												'label' => 'Découvrir les fonctionnalités',
												'title' => 'Découvrir les fonctionnalités de l\'application GMAO',
												'route' => 'application',
												'priority' => 0.9,
												'changefreq' => 'daily',
												'action' => 'fonction' 
										),
										array (
												'icon'	=> 'globe',
												'label' => 'Découvrir les services',
												'title' => 'Découvrir les services de AT-IT',
												'route' => 'application',
												'priority' => 0.7,
												'changefreq' => 'weekly',
												'action' => 'service' 
										),
										array (
												'icon'	=> 'code',
												'label' => 'Suivre les évolutions',
												'title' => 'Se connecter au portail de développement pour suivre les évolutions de GMAO',
												'route' => 'application',
												'priority' => 0.7,
												'changefreq' => 'daily',
												'action' => 'suivre' 
										),
										array (
												'icon'	=> 'phone',
												'label' => 'Contactez nous !',
												'title' => 'Accéder au formulaire de contact',
												'route' => 'application',
												'priority' => 0.3,
												'changefreq' => 'monthly',
												'action' => 'contact'
										),
																				
								) 
						),
						array(
								'icon'	=> 'sign-in',
								'label' => 'Login',
								'route' => 'user-index',
								'priority' => 0.8,
								'changefreq' => 'weekly',
								'action' => 'login'
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