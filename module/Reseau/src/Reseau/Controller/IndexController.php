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
use Reseau\Form\ReseauFilter;
use Zend\Http\PhpEnvironment\Response;

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
	 * @var Reseau\Entity\Ips
	 */
	protected $ipService;

	/**
	 * @var Reseau\Form\ReseauForm
	 */
	protected $reseauForm;
	/**
	 * @var Reseau\Form\ReservationIpForm
	 */
	protected $reservationIpForm;
	/**
	 * @var Reseau\Form\ReservationIpFilter
	 */
	protected $reservationIpFilter;


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
     * Consulter un des réseaux
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function consulterAction()
    {
    	//Récupération du réseau
    	$unReseau=$this->getReseauFromUrl();
    	if ($unReseau instanceof Response){
    		//Redirection
    		return $unReseau;
    	}

    	$ipService = $this->getIpService();

    	$ips = $ipService->rechercherLesIPDuReseauSelonId($unReseau);

    	$viewModel = new ViewModel(array(
    		'unReseau' => $unReseau,
    		'ips'	   => $ips
    	));
    	return $viewModel;
    }

    public function reserverIpAction()
    {
    	//Initialisation des variables
    	$reseauService = $this->getReseauService();
    	$unReseau = $this->getReseauFromUrl();
    	if ($unReseau instanceof Response){
    		//Redirection
    		return $unReseau;
    	}
    	$ipService = $this->getIpService();
    	$reservationIpForm   = $this->getReservationIpForm();
    	$reservationIpFilter = $this->getReservationIpFilter();

    	if($this->getRequest()->isPost()) {
            $reservationIpForm->setData($this->getRequest()->getPost());
            $reservationIpForm->setInputFilter($reservationIpFilter);
            if($reservationIpForm->isValid()) {
				//Création de l'Entité IP
            	$uneIp = $ipService->creerUneNouvelleIp($reservationIpForm,$unReseau);
            	if ($ipService->isUnique($uneIp,$unReseau)){
            		//Enregistrement
            		$ipService->enregistrerUneIp($uneIp);
            		$this->flashMessenger()->addSuccessMessage($this->getTranslatorHelper()->translate('L\'Ip a été référencée avec succès.', 'iptrevise'));
            		return $this->redirect()->toRoute('reseau',array('action'=>'consulter','reseau'=>$unReseau->getId()));
            	}else{
   	                //$array= $reservationIpForm->getMessages();
	                $array['ip']['isUnique']=$this->getTranslatorHelper()->translate(
            			'Cette adresse Ip est déjà déclaré dans ce réseau'
            		);
    	        	$reservationIpForm->setMessages($array);
            	}
            } else {
                //Marche pas
                //die('invalide');
            	$this->flashMessenger()->addErrorMessage($this->getTranslatorHelper()->translate('Les informations envoyées comportent des erreurs. Veuillez les rectifier puis les renvoyer !'));
            }
        }else{
        	//Initialisation du formulaire avec les données métier
        	$ip = $ipService->rechercherLaPremiereIpDisponibleDuReseau($unReseau);
        	// @var $reservationIpForm Reseau\Form\ReservationIpForm
        	$reservationIpForm->get('ip')->setValue(long2ip($ip));
        }

    	$viewModel = new ViewModel(array(
    			'unReseau' 			=> $unReseau,
    			'reservationIpForm'	=> $reservationIpForm
    	));
    	return $viewModel;
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
    	$unReseau = $this->getReseauFromUrl(true);
    	if ($unReseau instanceof Response){
    		//Redirection
    		return $unReseau;
    	}
		$confirmation = (int) $this->params()->fromRoute('confirmation', 0);

		if($confirmation == 1){
			$this->getReseauService()->supprimerUnReseau($unReseau);
			$message = sprintf($this->getTranslatorHelper()->translate("Réseau %s supprimé avec succès", 'iptrevise'),$unReseau->getCIDR());
			$this->flashMessenger()->addSuccessMessage($message);
			return $this->redirect()->toRoute('reseau');
		}elseif($confirmation == 2){
			$message = sprintf($this->getTranslatorHelper()->translate("Annulation demandée. Le réseau %s n'a pas été supprimé", 'iptrevise'),$unReseau->getCIDR());
			$this->flashMessenger()->addInfoMessage($message);
			return $this->redirect()->toRoute('reseau');
		}

		return new ViewModel(array('reseau' => $unReseau));

    }
	/**
     * Action de suppression d'une ip
	 * @return \Zend\Http\PhpEnvironment\Response|Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>|\Zend\View\Model\ViewModel
	 */
    public function supprimerIpAction(){
    	$unReseau = $this->getReseauFromUrl();
    	if ($unReseau instanceof Response){
    		//Redirection
    		return $unReseau;
    	}

    	$uneIp = $this->getIpFromUrl(true);
    	if ($uneIp instanceof Response){
    		//Redirection
    		return $uneIp;
    	}
    	$confirmation = (int) $this->params()->fromRoute('confirmation', 0);

    	if($confirmation == 1){
    		$this->getIpService()->supprimerUneIp($uneIp);
    		$message = sprintf($this->getTranslatorHelper()->translate("Adresse IP %s supprimée avec succès", 'iptrevise'),long2ip($uneIp->getIp()));
    		$this->flashMessenger()->addSuccessMessage($message);
    		return $this->redirect()->toRoute('reseau',array('action'=>'consulter','reseau'=>$unReseau->getId()));
    	}elseif($confirmation == 2){
    		$message = sprintf($this->getTranslatorHelper()->translate("Annulation demandée. L'adresse IP %s n'a pas été supprimée", 'iptrevise'),long2ip($uneIp->getIp()));
    		$this->flashMessenger()->addInfoMessage($message);
    		return $this->redirect()->toRoute('reseau',array('action'=>'consulter','reseau'=>$unReseau->getId()));
    	}

    	return new ViewModel(array(
    			'unReseau' => $unReseau,
    			'uneIp'	 => $uneIp,
    	));

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
     * get reservationIpFilter
     *
     * @return Reseau\Form\ReservationIpFilter
     */
    protected function getReservationIpFilter()
    {
    	if (null === $this->reservationIpFilter) {
    		$this->reservationIpFilter = $this->getServiceLocator()->get('ReservationIpFilter');
    	}

    	return $this->reservationIpFilter;
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
    /**
     *
     * @return \Reseau\Form\ReservationIpForm
     */
    protected function getReservationIpForm(){
    	if (null === $this->reservationIpForm) {
    		$this->reservationIpForm = $this->getServiceLocator()->get('ReservationIpForm');
    	}

    	return $this->reservationIpForm;
    }
    /**
     *
     * @return unknown
     */
    protected function getReseauFromUrl($writable = false){
    	$id = (int) $this->params()->fromRoute('reseau', 0);

    	if ($id == 0) {
    		$this->flashMessenger()->addErrorMessage($this->getTranslatorHelper()->translate('Identifiant réseau invalide', 'iptrevise'));
    		return $this->redirect()->toRoute('reseau');
    	}

    	$reseauService = $this->getReseauService();
        if ($writable){
        	//recherche d'une table
    		$rechercherUnReseauSelonId = 'rechercherUnReseauSelonId';
    	}else{
    		//recherche d'une vue
    		$rechercherUnReseauSelonId = 'rechercherUnReseauSelonIdEnLectureSeule';
    	}
    	$unReseau = $reseauService->$rechercherUnReseauSelonId($id);
    	if (empty($unReseau)){
    		$this->flashMessenger()->addWarningMessage($this->getTranslatorHelper()->translate('Le réseau sélectionné n\'a pas été trouvé ou n\'existe plus', 'iptrevise'));
    		return $this->redirect()->toRoute('reseau');
    	}
    	return $unReseau;
    }

    /**
     *
     * @return unknown
     */
    protected function getIpFromUrl($writable = false){
    	$id = (int) $this->params()->fromRoute('ip', 0);

    	if ($id == 0) {
    		$this->flashMessenger()->addErrorMessage($this->getTranslatorHelper()->translate('Identifiant Ip invalide', 'iptrevise'));
    		return $this->redirect()->toRoute('reseau');
    	}

    	$ipService = $this->getIpService();
    	if ($writable){
    		//recherche d'une table
    		$rechercherUneIpSelonId = 'rechercherUneIpSelonId';
    	}else{
    		//recherche d'une vue
    		$rechercherUneIpSelonId = 'rechercherUnIpSelonIdEnLectureSeule';
    	}
    	$uneIp = $ipService->$rechercherUneIpSelonId($id);
    	if (empty($uneIp)){
    		$this->flashMessenger()->addWarningMessage($this->getTranslatorHelper()->translate('Le réseau sélectionné n\'a pas été trouvé ou n\'existe plus', 'iptrevise'));
    		return $this->redirect()->toRoute('reseau');
    	}
    	return $uneIp;
    }

}
