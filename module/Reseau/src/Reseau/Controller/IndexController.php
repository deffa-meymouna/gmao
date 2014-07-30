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

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Reseau\Entity\Reseaux;
use Reseau\Form\ReseauForm;
use Reseau\Form\ReseauFilter;

class IndexController extends AbstractActionController
{
	/**
	 * @var ModuleOptions
	 */
	protected $options;

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $entityManager;

	/**
	 * @var Reseau\Entity\Reseaux
	 */
	protected $reseauService;

	/**
	 * @var Reseau\Form\ReseauForm
	 */
	protected $reseauForm;

	/**
	 * @var Zend\Mvc\I18n\Translator
	 */
	protected $translatorHelper;

	/**
	 * @var Zend\Form\Form
	 */
	protected $reseauFormHelper;

	/**
	 * Listing des réseaux
	 *
	 */
    public function indexAction()
    {
    	$reseauService = $this->getReseauService();
    	$reseaux = $reseauService->listerTousLesReseaux();
    	return new ViewModel(array('reseaux' => $reseaux));
    }
	/**
	 * Action de création d'un réseau
	 *
	 * @return Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>|\Zend\View\Model\ViewModel
	 */
    public function creerAction()
    {
		$form = $this->getReseauForm();

        $form->setAttributes(array(
            'action' => $this->url()->fromRoute('reseau', array('action' => 'creer')),
            'name' => 'creerUnReseau'
        ));
        $form->get('submit')->setAttributes(array(
                'value' => $this->getTranslatorHelper()->translate('Créer un reseau'),
        ));

        if($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            $form->setInputFilter(new ReseauFilter());
            if($form->isValid()) {
            	$reseauService = $this->getReseauService();
				$unReseau = $reseauService->creerUnNouveauReseau($form);
				//Enregistrement
				$reseauService->enregistrerUnReseau($unReseau);
                $this->flashMessenger()->addSuccessMessage($this->getTranslatorHelper()->translate('Le réseau a été créé avec succès', 'iptrevise'));
                return $this->redirect()->toRoute('reseau');
            } else {
                //Marche pas
                //die('invalide');
            	$this->flashMessenger()->addErrorMessage($this->getTranslatorHelper()->translate('Les informations envoyées comportent des erreurs. Veuillez les rectifier puis les renvoyer !'));
                //return $this->redirect()->toRoute('reseau');
            }
        }

        $viewModel = new ViewModel(array(
            'form' => $form,
            'headerLabel' => $this->getTranslatorHelper()->translate('Créer un réseau'),
        ));
        return $viewModel;
    }
    /**
     * Action de suppression d'un réseau
     *
     */
    public function supprimerAction(){
    	$id = (int) $this->params()->fromRoute('reseau', 0);

    	if ($id == 0) {
    		$this->flashMessenger()->addErrorMessage($this->getTranslatorHelper()->translate('Identifiant réseau invalide', 'iptrevise'));
    		return $this->redirect()->toRoute('reseau');
    	}

    	$reseauService = $this->getReseauService();
		$unReseau = $reseauService->rechercherUnReseauSelonId($id,$this->getEntityManager());
		if (empty($unReseau)){
			$this->flashMessenger()->addWarningMessage($this->getTranslatorHelper()->translate('Le réseau sélectionné n\'a pas été trouvé ou n\'existe plus', 'iptrevise'));
			return $this->redirect()->toRoute('reseau');
		}

		$confirmation = (int) $this->params()->fromRoute('confirmation', 0);

		if($confirmation == 1){
			Reseaux::supprimerUnReseau($unReseau,$this->getEntityManager());
			$message = sprintf($this->getTranslatorHelper()->translate("Réseau %s supprimé avec succès", 'iptrevise'),$unReseau->getCIDR());
			$this->flashMessenger()->addSuccessMessage($message);
			return $this->redirect()->toRoute('reseau');
		}elseif($confirmation == 2){
			$message = sprintf($this->getTranslatorHelper()->translate("Annulation demandée. Le réseau %s nà pas été supprimé", 'iptrevise'),$unReseau->getCIDR());
			$this->flashMessenger()->addInfoMessage($message);
			return $this->redirect()->toRoute('reseau');
		}

		return new ViewModel(array('reseau' => $unReseau));

    }

    /**
     * get reseauService
     *
     * @return Reseaux
     */
    protected function getReseauService()
    {
    	if (null === $this->reseauService) {
    		$this->reseauService = $this->getServiceLocator()->get('ReseauService');
    	}

    	return $this->reseauService;
    }

    /**
     * get reseauForm
     *
     * @return
     */
    protected function getReseauForm()
    {
    	if (null === $this->reseauForm) {
    		$this->reseauForm = $this->getServiceLocator()->get('ReseauForm');
    	}

    	return $this->reseauForm;
    }

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
