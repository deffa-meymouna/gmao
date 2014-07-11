<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Utilisateur for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Utilisateur\Controller;

use phpCAS;
use Zend\View\Model\ViewModel;
use Zend\Session\SessionManager;
use CsnUser\Entity\User;
use CsnUser\Options\ModuleOptions;
use Utilisateur\Authentication\Adapter\Cas as AdapterCas;

/**
 * Surcharge du controlleur initial de CsnUser
 *
 * @author alexandre
 *        
 */
class IndexController extends \CsnUser\Controller\IndexController {
	
	/**
	 *
	 * @var array
	 */
	protected $cerbere;
	
	/**
	 *
	 * @var ModuleOptions
	 */
	protected $options;
	
	/**
	 *
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $entityManager;
	
	/**
	 *
	 * @var Zend\Mvc\I18n\Translator
	 */
	protected $translatorHelper;
	
	/**
	 * Log in action
	 *
	 * The method uses Doctrine Entity Manager to authenticate the input data
	 *
	 * @return Zend\View\Model\ViewModel array form|array messages|array navigation menu
	 */
	public function loginAction() {
		$user = $this->identity ();
		if ( $user ) {
			return $this->redirect ()->toRoute ( $this->getOptions ()->getLoginRedirectRoute () );
		}
		
		$user = new User ();
		// authentification cerbere
		$authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
		//@FIXME detruire l'appel ci-dessous et le remplacer par $adapter=$authService->getAdapter();
		$adapter = new AdapterCas();
		$adapter->setOptions($this->getCerbere ());
		$result = $adapter->authenticate();
		$usernameOrEmail = $result->getIdentity();
		
		if (! empty ( $usernameOrEmail )) {
			try {
				$user = $this->getEntityManager ()->createQuery ( "SELECT u FROM CsnUser\Entity\User u WHERE u.email = '$usernameOrEmail' OR u.username = '$usernameOrEmail'" )->getResult ( \Doctrine\ORM\Query::HYDRATE_OBJECT );
				if (empty ( $user )) {
					// throw new \Exception());
					$messages = sprintf ( $this->getTranslatorHelper ()->translate ( 'Hello %s Your authentication credentials are not declared into this application' ), $usernameOrEmail );
					return new ViewModel ( array (
							'error' => $this->getTranslatorHelper ()->translate ( 'Your authentication credentials are not declared into this application' ),
							// 'form' => $form,
							'messages' => $messages,
							'navMenu' => $this->getOptions ()->getNavMenu () 
					) );
				} 
				$user = $user[0];
				
				if ($user->getState ()->getId () < 2) {
					$messages = $this->getTranslatorHelper ()->translate ( 'Your username is disabled. Please contact an administrator.' );
					return new ViewModel ( array (
							'error' => $this->getTranslatorHelper ()->translate ( 'Your authentication credentials are not valid' ),
							//'form' => $form,
							'messages' => $messages,
							'navMenu' => $this->getOptions ()->getNavMenu () 
					) );
				} else{
					//L'utilisateur existe et est déclaré !
					$identity = $user;
                    $authService->getStorage()->write($identity);
					if ($this->params()->fromPost('rememberme')) {
						$time = 1209600; // 14 days (1209600/3600 = 336 hours => 336/24 = 14 days)
						$sessionManager = new SessionManager();
						$sessionManager->rememberMe($time);
					}
				}
				

			} catch ( \Exception $e ) {
				return $this->getServiceLocator ()->get ( 'csnuser_error_view' )->createErrorView ( $this->getTranslatorHelper ()->translate ( 'Something went wrong during login! Please, try again later.' ), $e, $this->getOptions ()->getDisplayExceptions (), $this->getOptions ()->getNavMenu () );
			}
		}
	}
	
	/**
	 * Log out action
	 *
	 * The method destroys session for a logged user
	 *
	 * @return redirect to specific action
	 */
	public function logoutAction() {
		// Déconnexion du service
		$auth = $this->getServiceLocator ()->get ( 'Zend\Authentication\AuthenticationService' );
		if ($auth->hasIdentity ()) {
			$auth->clearIdentity ();
			$sessionManager = new SessionManager ();
			$sessionManager->forgetMe ();
		}
		// Déconnexion Cerbère
		//@FIXME Remplacer la ligne ci-dessous par 
		$adapter = new AdapterCas();
		$adapter->setOptions($this->getCerbere ());
		$result = $adapter->logout();
		
		// unused
		return $this->redirect ()->toRoute ( $this->getOptions ()->getLogoutRedirectRoute () );
	}
	/**
	 * set options
	 *
	 * @return IndexController
	 */
	private function setCerbere($cerbere) {
		$this->cerbere = $cerbere;
		
		return $this;
	}
	
	/**
	 * get options
	 *
	 * @return ModuleOptions
	 */
	public function getCerbere() {
		if (! $this->cerbere) {
			$this->setCerbere ( $this->getServiceLocator ()->get ( 'cerbere' ) );
		}
		
		return $this->cerbere;
	}
	/**
	 * get options
	 *
	 * @return ModuleOptions
	 */
	private function getOptions() {
		if (null === $this->options) {
			$this->options = $this->getServiceLocator ()->get ( 'csnuser_module_options' );
		}
		
		return $this->options;
	}
	/**
	 * get translatorHelper
	 *
	 * @return Zend\Mvc\I18n\Translator
	 */
	private function getTranslatorHelper() {
		if (null === $this->translatorHelper) {
			$this->translatorHelper = $this->getServiceLocator ()->get ( 'MvcTranslator' );
		}
		
		return $this->translatorHelper;
	}
	/**
	 * get entityManager
	 *
	 * @return EntityManager
	 */
	private function getEntityManager() {
		if (null === $this->entityManager) {
			$this->entityManager = $this->getServiceLocator ()->get ( 'doctrine.entitymanager.orm_default' );
		}
		
		return $this->entityManager;
	}
}
