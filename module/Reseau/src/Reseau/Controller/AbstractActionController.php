<?php
/**
 * Reseau - Reseau Module to use with Zend Framework 2 MVC 2
 *
 * @link https://github.com/Alexandre-T/gmao for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Alexandre Tranchant
 * @license https://github.com/Alexandre-T/gmao/blob/master/LICENSE MIT
 * @author Alexandre Tranchant <Alexandre.Tranchant@gmail.com>
 */

namespace Reseau\Controller;

use Zend\Mvc\Controller\AbstractActionController as ZendActionController;

abstract class AbstractActionController extends ZendActionController
{
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $entityManager;

	/**
	 * @var Reseau\Entity\Reseaux
	 */
	protected $reseauService;

	/**
	 * @var Reseau\Entity\Ips
	 */
	protected $ipService;

	/**
	 *
	 * @var Reseau\Entity\Machines
	 */
	protected $machineService;


	/**
	 * @var Zend\Mvc\I18n\Translator
	 */
	protected $translatorHelper;

    /**
     * get entityManager
     *
     * @return EntityManager
     */
    protected function getEntityManager()
    {
    	if (null === $this->entityManager) {
    		$this->entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    	}

    	return $this->entityManager;
    }

    /**
     *
     * @return unknown
     */
    protected function getIpFromUrl($writable = false,$redirectionAutomatique = true){
    	$id = (int) $this->params()->fromRoute('ip', 0);

    	if ($id == 0) {
    		if ($redirectionAutomatique){
    			$this->flashMessenger()->addErrorMessage($this->getTranslatorHelper()->translate('Identifiant Ip invalide', 'iptrevise'));
    			return $this->redirect()->toRoute('reseau');
    		} else {
    			return null;
    		}
    	}
    	$ipService = $this->getIpService();
    	if ($writable){
    		//recherche d'une table
    		$rechercherUneIpSelonId = 'rechercherUneIpSelonId';
    	}else{
    		//recherche d'une vue
    		$rechercherUneIpSelonId = 'rechercherUneIpSelonIdEnLectureSeule';
    	}
    	$uneIp = $ipService->$rechercherUneIpSelonId($id);
    	if (empty($uneIp)){
    		$this->flashMessenger()->addWarningMessage($this->getTranslatorHelper()->translate('L\'IP sélectionnée n\'a pas été trouvée ou n\'existe plus', 'iptrevise'));
    		return $this->redirect()->toRoute('reseau');
    	}
    	return $uneIp;
    }

    /**
     * get IPService
     *
     * @return Reseau\Entity\Ips
     */
    protected function getIpService()
    {
    	if (null === $this->ipService) {
    		$this->ipService = $this->getServiceLocator()->get('IpService');
    	}

    	return $this->ipService;
    }
    /**
     * get MachineService
     *
     * @return Reseau\Entity\Machines
     */
    protected function getMachineService()
    {
    	if (null === $this->machineService) {
    		$this->machineService = $this->getServiceLocator()->get('MachineService');
    	}

    	return $this->machineService;
    }
    /**
     * get reseauService
     *
     * @return Reseau\Entity\Reseaux
     */
    protected function getReseauService()
    {
    	if (null === $this->reseauService) {
    		$this->reseauService = $this->getServiceLocator()->get('ReseauService');
    	}

    	return $this->reseauService;
    }
    /**
     * get translatorHelper
     *
     * @return  Zend\Mvc\I18n\Translator
     */
    protected function getTranslatorHelper()
    {
    	if (null === $this->translatorHelper) {
    		$this->translatorHelper = $this->getServiceLocator()->get('MvcTranslator');
    	}

    	return $this->translatorHelper;
    }

}
