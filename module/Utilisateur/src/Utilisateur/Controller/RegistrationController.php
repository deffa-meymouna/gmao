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
use CsnUser\Options\ModuleOptions;

/**
 * Surcharge du controlleur initial de CsnUser
 *
 * @author alexandre
 *
 */
class RegistrationController extends \CsnUser\Controller\RegistrationController {

	/* (non-PHPdoc)
	 * @see \CsnUser\Controller\RegistrationController::changeEmailAction()
	*/
	public function changeEmailAction() {
		//Identification via Cerbere, je désactive cette action
		return $this->redirect()->toRoute('user-index');
	}

	/* (non-PHPdoc)
	 * @see \CsnUser\Controller\RegistrationController::changePasswordAction()
	*/
	public function changePasswordAction() {
		//Identification via Cerbere, je désactive cette action
		return $this->redirect()->toRoute('user-index');
	}

	/* (non-PHPdoc)
	 * @see \CsnUser\Controller\RegistrationController::changeSecurityQuestionAction()
	*/
	public function changeSecurityQuestionAction() {
		//Identification via Cerbere, je désactive cette action
		return $this->redirect()->toRoute('user-index');
	}

	/* (non-PHPdoc)
	 * @see \CsnUser\Controller\RegistrationController::confirmEmailAction()
	*/
	public function confirmEmailAction() {
		//Identification via Cerbere, je désactive cette action
		return $this->redirect()->toRoute('user-index');
	}

	/* (non-PHPdoc)
	 * @see \CsnUser\Controller\RegistrationController::confirmEmailChangePasswordAction()
	*/
	public function confirmEmailChangePasswordAction() {
		//Identification via Cerbere, je désactive cette action
		return $this->redirect()->toRoute('user-index');
	}



	/* (non-PHPdoc)
	 * @see \CsnUser\Controller\RegistrationController::indexAction()
	*/
	public function indexAction() {
		//Identification via Cerbere, je désactive cette action
		return $this->redirect()->toRoute('user-index');
	}

	/* (non-PHPdoc)
	 * @see \CsnUser\Controller\RegistrationController::resetPasswordAction()
	*/
	public function resetPasswordAction() {
		//Identification via Cerbere, je désactive cette action
		return $this->redirect()->toRoute('user-index');
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