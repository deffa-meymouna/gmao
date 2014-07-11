<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
return array (
		'db' => array (
				'driver' => 'Pdo',
				'driver_options' => array (
						PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'' 
				) 
		),
		'service_manager' => array (
				'factories' => array (
						'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
						'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
				) 
		),
		'mantis' => array (
				'soap' => 'http://127.0.0.1/mantis/api/soap/mantisconnect.php?wsdl',
				'projectId' => 1,
				'evolutionFilterId' => 2,
				'bugFilterId' => 3 
		),			
)
;