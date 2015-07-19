<?php
/**
 * Coolcsn Zend Framework 2 Navigation Module
 *
 * @link https://github.com/coolcsn/CsnAclNavigation for the canonical source repository
 * @copyright Copyright (c) 2005-2013 LightSoft 2005 Ltd. Bulgaria
 * @license https://github.com/coolcsn/CsnAclNavigation/blob/master/LICENSE BSDLicense
 * @authors Stoyan Cheresharov <stoyan@coolcsn.com>, Anton Tonev <atonevbg@gmail.com>
*/
/**
 *	pages model
 *		'icon'	=> 'home', // Font Awesome icon
 *		'label' => 'Home', // Label text
 *		'title' => 'home', //Title attribute for links
 *		'route' => 'home'  //route map
 *		'class' => Add a class to li tag
 *		'changefreq' => '' //For Sitemap, valid values are always hourly daily weekly monthly yearly never
 *		'lastmod' => '' //For Sitemap, valid values are W3C Date or YYYY-MM-DD
 *		'priority' => '1.0' //The value should be a decimal between 0.0 and 1.0
 *		'resource' => '', //resource declared into security.global.php
 *		'privilege' => 'index' //privilege ie action
*/
return array (
	'navigation' => array (
		'default' => array (
			array (
				'icon' => 'home',
				'label' => 'Home',
				'title' => 'Blanche',
				'route' => 'home',
				'priority' => 1,
				'changefreq' => 'weekly',
				'resource' => 'homePage',
			    'privilege' => 'view'
			),
			array (
				'icon' => 'road',
				'label' => 'Exemple',
				'route' => 'application',
				'priority' => 0.8,
				'changefreq' => 'weekly',
			    'resource' => 'examples',
			    'privilege' => 'view',
				'pages' => array (
					array (
						'icon' => 'eye',
						'label' => 'Sous-Exemple 1.1',
						'title' => 'Editez cette section 1.1',
						'route' => 'application',
						'priority' => 0.9,
						'changefreq' => 'daily',
						'action' => 'exemple1',
					    'resource' => 'examples',
			            'privilege' => 'view',
					),
					array (
						'icon' => 'globe',
						'label' => 'Sous-Exemple 1.2',
						'title' => 'Editez cette section 1.1',
						'route' => 'application',
						'priority' => 0.7,
						'changefreq' => 'weekly',
						'action' => 'exemple2',
					    'resource' => 'examples',
			            'privilege' => 'view',
					),
					array (
						'icon' => 'code',
						'label' => 'Suivre les évolutions',
						'title' => 'Se connecter au portail de développement pour suivre les évolutions de l\'application blanche',
						'route' => 'application',
						'priority' => 0.7,
						'changefreq' => 'daily',
						'action' => 'suivre',
					    'resource' => 'examples',
			            'privilege' => 'view',
					),
					array (
						'icon' => 'phone',
						'label' => 'Contactez nous !',
						'title' => 'Accéder au formulaire de contact',
						'route' => 'application',
						'priority' => 0.3,
						'changefreq' => 'monthly',
						'action' => 'contact',
					    'resource' => 'examples',
			            'privilege' => 'view',
					)
				)
			),
		    array (
		        'icon' => 'wrench',
		        'label' => 'Administration',
		        'route' => 'zfcadmin',
		        'priority' => 1,
		        'changefreq' => 'monthly',
		        'resource' => 'administration',
		        'privilege' => 'list',
		        'pages' => array (
		            array (
		                'icon' => 'users',
		                'label' => 'Utilisateurs',
		                'title' => 'Gestion des utilisateurs',
		                'route' => 'zfcadmin/user',
		                'priority' => 0.9,
		                'changefreq' => 'monthly',
		                'resource' => 'user',
		                'privilege' => 'list',
		            ),
		            array (
		                'icon' => 'shield',
		                'label' => 'Roles',
		                'title' => 'Gestion des rôles',
		                'route' => 'zfcadmin/role',
		                'priority' => 0.9,
		                'changefreq' => 'monthly',
		                'action' => 'list',
		                'resource' => 'role',
		                'privilege' => 'list',
		            ),
		            
		        ),
		    ),
		 )
    )
);