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
    	$reseauService = new Reseaux($this->getEntityManager());
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
		$form = new ReseauForm();

        $form->setAttributes(array(
            'action' => $this->url()->fromRoute('reseau', array('action' => 'creer')),
            'name' => 'creerUnReseau'
        ));
        $form->get('submit')->setAttributes(array(
                'value' => $this->getTranslatorHelper()->translate('Créer un reseau'),
        ));

        if($this->getRequest()->isPost()) {
        	$entityManager = $this->getEntityManager();
            $form->setData($this->getRequest()->getPost());
            $form->setInputFilter(new ReseauFilter());
            if($form->isValid()) {
				$unReseau = Reseaux::creerUnNouveauReseau($form);
				//Enregistrement
				Reseaux::enregistrerUnReseau($unReseau,$entityManager);
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
        //$viewModel->setTemplate('csn-authorization/role-admin/role-form');
        return $viewModel;
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
