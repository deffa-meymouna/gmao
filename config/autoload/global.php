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
		'service_manager' => array (
				'factories' => array (
						'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
						'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
						'doctrine.sql_logger' => function () {
							$writer = new Zend\Log\Writer\Stream('data/log/logger_doctrine_sql.log');
							$logger = new Zend\Log\Logger();
							$logger->addWriter($writer);
							return $logger;
						},
				)
		),
		//Configuration
		'mantis' => array (
				//Enter the number project in your Mantis
				'projectId' => 1,
				//Enter the evolution filter Id
				'evolutionFilterId' => 2,
				//Enter the bug filter id
				'bugFilterId' => 3
		),
		'ZF2DoctrineSQLLogger' => array(
				'entitymanager'     => 'doctrine.entitymanager.orm_default',
				'logger'            => 'doctrine.sql_logger',
				'priority'          => Zend\Log\Logger::NOTICE,
				'log_executiontime' => false,
		),
)
;