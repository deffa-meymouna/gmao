<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module {
	public function onBootstrap(MvcEvent $e) {
		$eventManager = $e->getApplication ()->getEventManager ();
		$moduleRouteListener = new ModuleRouteListener ();
		$moduleRouteListener->attach ( $eventManager );
		$translator = $e->getApplication ()->getServiceManager ()->get ( 'translator' );
		if (isset ( $_SERVER ['HTTP_ACCEPT_LANGUAGE'] )) {
			$translator->setLocale ( \Locale::acceptFromHttp ( $_SERVER ['HTTP_ACCEPT_LANGUAGE'] ) )->setFallbackLocale ( 'fr_FR' );
		} else {
			$translator->setLocale ( 'fr_FR' );
		}
	}
	public function getConfig() {
		return include __DIR__ . '/config/module.config.php';
	}
	public function getAutoloaderConfig() {
		return array (
				'Zend\Loader\ClassMapAutoloader' => array(
						__DIR__ . '/autoload_classmap.php',
				),
				'Zend\Loader\StandardAutoloader' => array (
						'namespaces' => array (
								__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
						) 
				) 
		);
	}
	public function getServiceConfig() {
		return array (
				'factories' => array (
						'mantis' => function ($serviceManager) {
							$config = $serviceManager->get('Config');							
							return $config ['mantis'];
						} 
				) 
		);
	}
}
