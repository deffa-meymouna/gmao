<?php

namespace Reseau\Controller;

use Zend\View\Model\ViewModel;
use Zend\Http\PhpEnvironment\Response;
use Reseau\Controller\AbstractActionController;
use Reseau\Form\MachineFilter;
use Reseau\Entity\Abs\Machine;
use Reseau\Form\IpPourMachineFilter;
use Reseau\Entity\Abs\Ip;


/**
 * MachineController
 *
 * @author
 *
 * @version
 *
 */
class MachineController extends AbstractActionController {

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $entityManager;

	/**
	 *
	 */
	protected $machineForm;

	protected $ipPourMachineForm;


	public function associerIpAction(){
		//Récupération de la machine
		$uneMachine=$this->getMachineFromUrl(true);
		if ($uneMachine instanceof Response){
			//Redirection
			return $uneMachine;
		}
		//Récupération du réseau
		$unReseau=$this->getReseauFromUrl(true);
		if ($unReseau instanceof Response){
			//Redirection
			return $unReseau;
		}
		$ipService = $this->getIpService();
		$uneIp = $this->getIpFromUrl(true,false);

		if ($uneIp instanceof Ip){

			$confirmation = (int) $this->params()->fromRoute('confirmation', 0);

			if($this->identity()->getId() === $uneIp->getCreateurId() || 1 == $confirmation){
				//sauvegarde
				$uneIp->setMachine($uneMachine);
				$ipService->enregistrerUneIp($uneIp);
				$message = $this->getTranslatorHelper()->translate('La machine %s a été associée avec succès à l\'IP %s', 'iptrevise');
				$message = sprintf($message,$uneMachine->getLibelle(),long2Ip($uneIp->getIp()));
				$this->flashMessenger()->addSuccessMessage($message);
				return $this->redirect()->toRoute('machine',array('action'=>'consulter','machine'=>$uneMachine->getId()));
			}elseif (2 == $confirmation){
				//confirmation refusée
				$message = $this->getTranslatorHelper()->translate('Annulation demandée. La machine %s n\'a pas été associée à l\'IP %s', 'iptrevise');
				$message = sprintf($message,$uneMachine->getLibelle(),long2Ip($uneIp->getIp()));
				$this->flashMessenger()->addWarningMessage($message);
				return $this->redirect()->toRoute('machine',array('action'=>'associerIp','machine'=>$uneMachine->getId(),'reseau'=>$unReseau->getId()));
			}else{
				//demande de confirmation
				$view = new ViewModel(array(
						'uneIp' 		=> $uneIp,
						'uneMachine'	=> $uneMachine,
						'unReseau'		=>$unReseau
				));
				$view->setTemplate('reseau/machine/confirmer-association-ip');
				return $view;
			}

			die('oups');
		}else{
			$ips = $ipService->rechercherLesIpSansMachineDuReseau($unReseau);
			return new ViewModel(array(
					'ips' => $ips,
					'uneMachine'	=> $uneMachine,
					'unReseau'	=>$unReseau
			));
		}
	}
	/**
	 * Listing des machines
	 *
	 * @return \Zend\View\Model\ViewModel
	 */
	public function indexAction() {
		$machineService = $this->getMachineService();
    	$machines = $machineService->listerToutesLesMachines();
    	return new ViewModel(array('machines' => $machines));
	}

	/**
	 * Consulter une des machines
	 *
	 * @return \Zend\View\Model\ViewModel
	 */
	public function consulterAction()
	{
		//Récupération du réseau
		$uneMachine=$this->getMachineFromUrl();
		if ($uneMachine instanceof Response){
			//Redirection
			return $uneMachine;
		}
		$createur  = $uneMachine->getCreateur();
		$ipService = $this->getIpService();
		$reseauService = $this->getReseauService();

		$ips = $ipService->rechercherLesIPDUneMachine($uneMachine);
		$reseaux = $reseauService->listerTousLesReseaux();

		$viewModel = new ViewModel(array(
				'reseaux'	 => $reseaux,
				'uneMachine' => $uneMachine,
				'createur' => $createur,
				'ips'	   => $ips
		));
		return $viewModel;
	}

	/**
	 * Cas d'utilisation : Consulter une IP d'un des réseaux
	 *
	 * @return Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>|\Zend\View\Model\ViewModel
	 */
	public function consulterIpAction()
	{
		//Récupération de l'ip
		$uneIp=$this->getIpFromUrl();
		if ($uneIp instanceof Response){
			//Redirection
			return $uneIp;
		}
		//Recherche du réseau associé
		$reseauService = $this->getReseauService();
		$unReseau  = $reseauService->rechercherUnReseauSelonId($uneIp->getReseauId());
		//Recherche de la machine associée
		$machineService = $this->getMachineService();
		$uneMachine = $machineService->rechercherUneMachineSelonId($uneIp->getMachineId());
		//Transmission à la vue
		$viewModel = new ViewModel(array(
				'uneIp'      => $uneIp,
				'uneMachine' => $uneMachine,
				'unReseau'   => $unReseau ,
		));
		return $viewModel;
	}
	/**
	 * Cas d'utilisation : Création d'une machine
	 *
	 * @return Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>|\Zend\View\Model\ViewModel
	 */
	public function creerAction(){
		$form = $this->getMachineForm();

		$form->setAttributes(array(
				'action' => $this->url()->fromRoute('machine', array('action' => 'creer')),
				'name' => 'creerUneMachine'
		));
		$form->get('submit')->setAttributes(array(
				'value' => $this->getTranslatorHelper()->translate('Référencer une machine'),
		));

		if($this->getRequest()->isPost()) {
			$form->setData($this->getRequest()->getPost());
			$form->setInputFilter(new MachineFilter());
			if($form->isValid()) {
				$machineService = $this->getMachineService();
				$uneMachine = $machineService->creerUneNouvelleMachine($form);
				$uneMachine->setCreateur($this->identity());
				//Enregistrement
				$machineService->enregistrerUneMachine($uneMachine);
				$this->flashMessenger()->addSuccessMessage($this->getTranslatorHelper()->translate('La machine a été enregistrée avec succès', 'iptrevise'));
				return $this->redirect()->toRoute('machine');
			} else {
				//Marche pas
				//die('invalide');
				$this->flashMessenger()->addErrorMessage($this->getTranslatorHelper()->translate('Les informations envoyées comportent des erreurs. Veuillez les rectifier puis les renvoyer !'));
				//return $this->redirect()->toRoute('reseau');
			}
		}

		$viewModel = new ViewModel(array(
				'form' => $form,
				'headerLabel' => $this->getTranslatorHelper()->translate('Référencer une nouvelle machine'),
		));
		return $viewModel;
	}
	/**
	 * Action qui crée une IP et l'associe à la machine courante
	 *
	 * @return \Zend\Http\PhpEnvironment\Response|Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>|\Zend\View\Model\ViewModel
	 */
	public function creerIpAction(){
		$uneMachine = $this->getMachineFromUrl(true);
		if ($uneMachine instanceof Response){
			//Redirection
			return $uneMachine;
		}
		$unReseau = $this->getReseauFromUrl(true);
		if ($unReseau instanceof Response){
			//Redirection
			return $unReseau;
		}
		$ipService = $this->getIpService();
		$form = $this->getCreerIpPourMachineForm();
		$form->get('machine')->setValue($uneMachine->getLibelle());
		$form->get('ip')->setValue(long2ip($ipService->rechercherLaPremiereIpDisponibleDuReseau($unReseau)));

		if($this->getRequest()->isPost()) {
			$form->setData($this->getRequest()->getPost());
			$form->setInputFilter(new IpPourMachineFilter());
			if($form->isValid()) {
				$uneIp = $ipService->creerUneNouvelleIpPourMachine($form,$unReseau,$uneMachine);
				$uneIp->setCreateur($this->identity());
				if ($ipService->isUnique($uneIp,$unReseau)){
					//Enregistrement
					$ipService->enregistrerUneIp($uneIp);
					$message = $this->getTranslatorHelper()->translate('La machine %s a été associée avec succès à l\'IP %s', 'iptrevise');
					$message = sprintf($message,$uneMachine->getLibelle(),long2Ip($uneIp->getIp()));
					$this->flashMessenger()->addSuccessMessage($message);
					return $this->redirect()->toRoute('machine',array('action'=>'consulter','machine'=>$uneMachine->getId()));
				}else{
					//$array= $reservationIpForm->getMessages();
					$array['ip']['isUnique']=$this->getTranslatorHelper()->translate(
							'Cette adresse Ip est déjà déclaré dans ce réseau'
					);
					$form->setMessages($array);
				}
			} else {
				//Marche pas
				//die('invalide');
				$this->flashMessenger()->addErrorMessage($this->getTranslatorHelper()->translate('Les informations envoyées comportent des erreurs. Veuillez les rectifier puis les renvoyer !'));
				//return $this->redirect()->toRoute('reseau');
			}
		}
		$viewModel = new ViewModel(array(
				'form' 			=> $form,
				'uneMachine'	=> $uneMachine,
				'unReseau'		=> $unReseau,
		));
		return $viewModel;
	}

	/**
	 * Action de suppression d'un réseau
	 *
	 */
	public function supprimerAction(){
		$uneMachine = $this->getMachineFromUrl(true);
		if ($uneMachine instanceof Response){
			//Redirection
			return $uneMachine;
		}
		$confirmation = (int) $this->params()->fromRoute('confirmation', 0);

		if($confirmation == 1){
			$supprimerIP = (int) $this->params()->fromRoute('ip', 0);
			if (2 == $supprimerIP){
				$this->getMachineService()->supprimerUneMachine($uneMachine,false);
				$message = "La machine %s a été supprimée avec succès. Elle ne disposait pas d'adresse IP.";
			}elseif (1 == $supprimerIP){
				$this->getMachineService()->supprimerUneMachine($uneMachine,true);
				$message = "La machine %s et les adresses IPs associées ont été supprimées avec succès";
			}else{
				$this->getMachineService()->supprimerUneMachine($uneMachine,false);
				$message = "La machine %s a été supprimée avec succès. Ces IPs ont été préservées et dissociées de toute machine.";
			}
			$message = sprintf($this->getTranslatorHelper()->translate($message, 'iptrevise'),$uneMachine->getLibelle());
			$this->flashMessenger()->addSuccessMessage($message);
			return $this->redirect()->toRoute('machine');
		}elseif($confirmation == 2){
			$message = sprintf($this->getTranslatorHelper()->translate("Annulation demandée. La machine %s n'a pas été supprimée", 'iptrevise'),$uneMachine->getLibelle());
			$this->flashMessenger()->addInfoMessage($message);
			return $this->redirect()->toRoute('machine', array('action'=>'consulter','machine'=>$uneMachine->getId()));
		}

		$ipService = $this->getIpService();
		$ips = $ipService->rechercherLesIPDUneMachine($uneMachine);

		return new ViewModel(array(
			'uneMachine' => $uneMachine,
			'ips'	  => $ips,
		));

	}

	/**
	 * Action de suppression d'un réseau
	 *
	 */
	public function dissocierIpAction(){
		$uneIp = $this->getIpFromUrl(true);
		if ($uneIp instanceof Response){
			//Redirection
			return $uneIp;
		}

		$uneMachine = $uneIp->getMachine();
		if (null == $uneMachine){
			$message = sprintf($this->getTranslatorHelper()->translate('Cette adresse IP n\'était associée à aucune machine.', 'iptrevise'),$uneIp->getLibelle());
			$this->flashMessenger()->addErrorMessage($message);
			return $this->redirect()->toRoute('reseau',array('action' => 'consulter','reseau' => $uneIp->getReseau()->getId()));
		}

		$confirmation = (int) $this->params()->fromRoute('confirmation', 0);
		if($confirmation == 1){
			$uneIp->setMachine(null);
			$ipService = $this->getIpService();
			$ipService->enregistrerUneIp($uneIp);
			$message = sprintf("L'adresse IP %s est dissociée de la machine %s",long2ip($uneIp->getIp()),$uneMachine->getLibelle());
			$message = $this->getTranslatorHelper()->translate($message,'iptrevise');
			$this->flashMessenger()->addSuccessMessage($message);
			return $this->redirect()->toRoute('machine');
		}elseif($confirmation == 2){
			$message = sprintf("Annulation demandée. L'adresse IP %s est toujours associée à la machine %s",long2ip($uneIp->getIp()),$uneMachine->getLibelle());
			$message = $this->getTranslatorHelper()->translate($message,'iptrevise');
			$this->flashMessenger()->addInfoMessage($message);
			return $this->redirect()->toRoute('machine',array('action'=>'consulter','machine'=>$uneMachine->getId()));
		}

		return new ViewModel(array(
				'uneMachine' => $uneMachine,
				'uneIp'	  => $uneIp,
		));

	}


	/**
	 * get machineForm
	 *
	 * @return
	 */
	protected function getMachineForm()
	{
		if (null === $this->machineForm) {
			$this->machineForm = $this->getServiceLocator()->get('MachineForm');
		}

		return $this->machineForm;
	}
	/**
	 * getCreerIpPourMachineForm
	 *
	 * @return
	 */
	protected function getCreerIpPourMachineForm()
	{
		if (null === $this->ipPourMachineForm) {
			$this->ipPourMachineForm = $this->getServiceLocator()->get('IpPourMachineForm');
		}

		return $this->ipPourMachineForm;
	}

	/**
	 *
	 * @return unknown
	 */
	protected function getMachineFromUrl($writable = false){
		$id = (int) $this->params()->fromRoute('machine', 0);

		if ($id == 0) {
			$this->flashMessenger()->addErrorMessage($this->getTranslatorHelper()->translate('Identifiant machine invalide', 'iptrevise'));
			return $this->redirect()->toRoute('machine');
		}

		$machineService = $this->getMachineService();
		if ($writable){
			//recherche d'une table
			$rechercherUneMachineSelonId = 'rechercherUneMachineSelonId';
		}else{
			//recherche d'une vue
			$rechercherUneMachineSelonId = 'rechercherUneMachineSelonIdEnLectureSeule';
		}
		$uneMachine = $machineService->$rechercherUneMachineSelonId($id);
		if (empty($uneMachine)){
			$this->flashMessenger()->addWarningMessage($this->getTranslatorHelper()->translate('La machine sélectionnée n\'a pas été trouvée ou n\'existe plus', 'iptrevise'));
			return $this->redirect()->toRoute('machine');
		}
		return $uneMachine;
	}
	/**
	 *
	 * @return unknown
	 */
	protected function getReseauFromUrl($writable = false, Machine $uneMachine = null){
		if( null == $uneMachine){
			$route = null;
		}else{
			$route = array('action'=>'consulter','machine' => $uneMachine->getId());
		}
		$id = (int) $this->params()->fromRoute('reseau', 0);

		if ($id == 0) {
			$this->flashMessenger()->addErrorMessage($this->getTranslatorHelper()->translate('Identifiant réseau invalide', 'iptrevise'));
			return $this->redirect()->toRoute('machine',$route);
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
			return $this->redirect()->toRoute('machine',$route);
		}
		return $unReseau;
	}
}