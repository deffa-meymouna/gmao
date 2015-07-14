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
 *		'resource' => '', //resource declared into @FIXME
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
			/*array(
				'icon' => 'user',
				'label' => 'Utilisateurs',
				'route' => 'user-admin',
				'priority' => 0.4,
				'changefreq' => 'weekly',
				'resource' => 'CsnUser\Controller\Admin',
				'privilege' => 'index',
				'pages' => array (
					array (
						'icon' => 'user',
						'label' => 'Créer un utilisateur',
						'title' => 'Créer un utilisateur',
						'route' => 'user-admin',
						'priority' => 0.9,
						'changefreq' => 'daily',
						'action' => 'create-user',
						'resource' => 'CsnUser\Controller\Admin',
						'privilege' => 'create-user',
					),
					array (
						'icon' => 'list',
						'label' => 'Liste des utilisateurs',
						'title' => 'Tableau d\'administration des utilisateurs',
						'route' => 'user-admin',
						'priority' => 0.9,
						'changefreq' => 'daily',
						'action' => 'index',
						'resource' => 'CsnUser\Controller\Admin',
						'privilege' => 'index',
					),
				),
			),
			array(
				'icon' => 'users',
				'label' => 'Rôles',
				'route' => 'role-admin',
				'priority' => 0.4,
				'changefreq' => 'weekly',
				'resource' => 'CsnAuthorization\Controller\RoleAdmin',
				'pages' => array (
					array (
						'icon' => 'user',
						'label' => 'Créer un rôle',
						'title' => 'Créer un rôle',
						'route' => 'role-admin',
						'priority' => 0.9,
						'changefreq' => 'daily',
						'action' => 'create-role',
						'resource' => 'CsnAuthorization\Controller\RoleAdmin',
						'privilege' => 'create-role',
					),
					array (
						'icon' => 'list',
						'label' => 'Liste des rôles',
						'title' => 'Tableau d\'administration des rôles',
						'route' => 'role-admin',
						'priority' => 0.9,
						'changefreq' => 'daily',
						'action' => 'index',
						'resource' => 'CsnAuthorization\Controller\RoleAdmin',
						'privilege' => 'index',
					),
				),
			)*/
/*,
			 array(
                 'label' => 'Login',
                 'route' => 'login',
				 'controller' => 'Index',
				 'action'     => 'login',
				 'resource'	  => 'Utilisateur\Controller\Index',
				 'privilege'  => 'login',
             ),
			 array(
                 'label' => 'User',
                 'route' => 'user',
				 'controller' => 'Index',
				 'action'     => 'index',
				 'resource'	  => 'Utilisateur\Controller\Index',
				 'privilege'  => 'index',
             ),
             array(
                 'label' => 'Registration',
                 'route' => 'registration',
				 'controller' => 'Registration',
				 'action'     => 'index',
				 'resource'	  => 'Utilisateur\Controller\Registration',
				 'privilege'  => 'index',
				 'title'	  => 'Registration Form'
             ),
             array(
                 'label' => 'Edit profile',
                 'route' => 'editProfile',
				 'controller' => 'Registration',
				 'action'     => 'editProfile',
				 'resource'	  => 'Utilisateur\Controller\Registration',
				 'privilege'  => 'editProfile',
             ),
			array(
				'label' => 'Zend',
				'uri'   => 'http://framework.zend.com/',
				'resource' => 'Zend',
				'privilege'	=>	'uri'
			),
			/*
			// uncomment if you have the CsnCms module installed
			array(
                 'label' => 'CMS',
                 'route' => 'csn-cms',
				 'controller' => 'Index',
				 'action'     => 'index',
				 'resource'	  => 'CsnCms\Controller\Index',
				 'privilege'  => 'index'
             ),
	        array(
                 'label' => 'Logout',
                 'route' => 'logout',
				 'controller' => 'Index',
				 'action'     => 'logout',
				 'resource'	  => 'Utilisateur\Controller\Index',
				 'privilege'  => 'logout'
             ),
			*/
		 )
		)
);