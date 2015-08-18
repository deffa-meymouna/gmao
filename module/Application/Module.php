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
use Zend\Validator\AbstractValidator;
use Zend\View\Helper\Navigation;

class Module {
	public function onBootstrap(MvcEvent $e) {
		
	    $eventManager = $e->getApplication ()->getEventManager ();
		$moduleRouteListener = new ModuleRouteListener ();
		$moduleRouteListener->attach ( $eventManager );
		
		//Détermination de la locale
		if (isset ( $_SERVER ['HTTP_ACCEPT_LANGUAGE'] )) {
		    $locale = \Locale::acceptFromHttp ( $_SERVER ['HTTP_ACCEPT_LANGUAGE'] );
		} else {
		    $locale = \Locale::getDefault();
		}
		//Initialisation de la traduction
		$translator = $e->getApplication ()->getServiceManager ()->get ( 'translator' );
		$translator->setLocale ( $locale );
		//Ajout des fichiers de traduction
		$sm_locale = substr($translator->getLocale(),0,2);
		$translationFile = __DIR__ . "/../../vendor/zendframework/zend-i18n-resources/languages/$sm_locale/Zend_Validate.php";
		if (file_exists($translationFile)){
		  $translator->addTranslationFile('phpArray',$translationFile);
		}
		AbstractValidator::setDefaultTranslator($translator);
		
		//Définition du rôle par défaut
		$provider =  $e->getApplication ()->getServiceManager ()->get('BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider');
		$roleService = $e->getApplication ()->getServiceManager ()->get('roleService');
		$provider->setDefaultRole($roleService->getRoleByDefault());
		$provider->setAuthenticatedRole($roleService->getAuthenticatedRole());
		
		// Add ACL information to the Navigation view helper
		$authorize = $e->getApplication ()->getServiceManager ()->get('BjyAuthorizeServiceAuthorize');
		$role = $authorize->getIdentity();
		$acl = $authorize->getAcl();
		
		Navigation::setDefaultAcl($acl);
		Navigation::setDefaultRole($role);
		
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
						},
				)
		);
	}
}
