<?php
/**
 * CsnAuthorization - Coolcsn Zend Framework 2 Authorization Module
 *
 * @link https://github.com/coolcsn/CsnAuthorization for the canonical source repository
 * @copyright Copyright (c) 2005-2013 LightSoft 2005 Ltd. Bulgaria
 * @license https://github.com/coolcsn/CsnAuthorization/blob/master/LICENSE BSDLicense
 * @author Stoyan Cheresharov <stoyan@coolcsn.com>
 * @author Stoyan Revov <st.revov@gmail.com>
 * @author Martin Briglia <martin@mgscreativa.com>
 *
 * CsnAuthorization Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 *
 * The idea of this config file is to define some basic ACL settings and to give the developer
 * the chance to define some gereric rules that not need to get loaded from database, like, rules for
 * Application standard skeleton app module or Zend Developer Tools Doctrine ORM Module YumlController for drawing
 * Doctrine entities dependency graph.
 *
 * For the more application speceific rules definition, CsnAuthorization provides the Roles admin panel,
 * that may be tweaked for specific module ACL options.
 */
return array(
    'csnauthorization' => array(
        /**
         * Set the default role name for not logged in users
         */
        'default_role' => 'guest',
        /**
         * Basic ACL, it should remain as tiny as possible
         */
        'roles' => array(
            'guest' => null,
            'member' => 'guest',
            'technician' => 'member',
            'admin' => 'technician',
        ),
        'resources' => array(
            'allow' => array(
                /**
                 *  ACL for CsnCms articles
                 */
                'all' => array(
                    'view'	=> 'guest',
                ),
                'Public Resource' => array(
                    'view'	=> 'guest',
                ),
                'Private Resource' => array(
                    'view'	=> 'member',
                ),
                'Admin Resource' => array(
                    'view'	=> 'admin',
                ),
                /**
                 * At least all users must be able to see Zend Skeleton Application
                 * standard home page.
                 */
                'Application\Controller\Index' => array(
                    'all' => 'guest',
                ),
                /**
                 * This Application
                 */
                'Cerbere\Controller\Index' => array(
                    'all' => 'guest',
                ),
                'Cerbere\Controller\Registration' => array(
                	'all' => 'member',
                ),
                'CsnAuthorization\Controller\RoleAdmin' => array(
                	'all' => 'admin',
                ),
                'CsnUser\Controller\Admin' => array(
                	'all' => 'admin',
                ),
                'CsnUser\Controller\Registration' => array(
               		'all' => 'member',
                ),
                /**
                 * This rule is for Zend Developer Tools Doctrine ORM Module YumlController for drawing
                 * Doctrine entities dependency graph. You can remove it if you don't plan to use
                 * Zend Developer Tools, or Doctrine ORM Module YumlController drawing function.
                 */
                'DoctrineORMModule\Yuml\YumlController' => array(
                    'index' => 'guest',
                ),
            ),
            'deny' => array(

            ),
        ),
    ),
);
