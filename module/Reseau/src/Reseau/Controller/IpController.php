<?php

namespace Reseau\Controller;

use Zend\View\Model\ViewModel;
use Zend\Http\PhpEnvironment\Response;
use Reseau\Controller\AbstractActionController;
use Reseau\Entity\Abs\Machine;
use Reseau\Form\MachinePourIpFilter;


/**
 * MachineController
 *
 * @author
 *
 * @version
 *
 */
class IpController extends AbstractActionController {

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $entityManager;

	/**
	 *
	 */
	protected $machineForm;
	/**
	 *
	 * @var
	 */
	protected $machinePourIpForm;

	public function associerMachineAction(){
		//Récupération de la machine
		$uneIp=$this->getIpFromUrl(true);
		if ($uneIp instanceof Response){
			//Redirection
			return $uneIp;
		}
		//Récupération du réseau
		$unReseau=$uneIp->getReseau();
		$uneMachine = $this->getMachineFromUrl(true,false);

		if ($uneMachine instanceof Machine){

			$confirmation = (int) $this->params()->fromRoute('confirmation', 0);

			if($this->identity()->getId() === $uneIp->getCreateurId() || 1 == $confirmation){
				//sauvegarde
				$uneIp->setMachine($uneMachine);
				$ipService = $this->getIpService();
				$ipService->enregistrerUneIp($uneIp);
				$message = $this->getTranslatorHelper()->translate('L\'IP %s a été associée avec succès à la machine %s', 'iptrevise');
				$message = sprintf($message,long2Ip($uneIp->getIp()),$uneMachine->getLibelle());
				$this->flashMessenger()->addSuccessMessage($message);
				return $this->redirect()->toRoute('ip',array('action'=>'consulter','ip'=>$uneIp->getId()));
			}elseif (2 == $confirmation){
				//confirmation refusée
				$message = $this->getTranslatorHelper()->translate('Annulation demandée. L\'IP %s n\'a pas été associée à la machine %s', 'iptrevise');
				$message = sprintf($message,long2Ip($uneIp->getIp()),$uneMachine->getLibelle());
				$this->flashMessenger()->addWarningMessage($message);
				return $this->redirect()->toRoute('ip',array('action'=>'associerMachine','ip'=>$uneIp->getId(),'reseau'=>$unReseau->getId()));
			}else{
				//demande de confirmation
				$view = new ViewModel(array(
						'uneIp' 		=> $uneIp,
						'uneMachine'	=> $uneMachine,
						'unReseau'		=>$unReseau
				));
				//@FIXME créer cette vue
				$view->setTemplate('reseau/ip/confirmer-association-machine');
				return $view;
			}
		}else{
			$machineService = $this->getMachineService();
			$machines = $machineService->listerToutesLesMachines();
			return new ViewModel(array(
					'machines' => $machines,
					'uneIp'	=> $uneIp,
					'unReseau'	=>$unReseau
			));
		}
	}

	/**
	 * Action qui crée une Machine et l'associe à l'IP courante
	 *
	 * @return \Zend\Http\PhpEnvironment\Response|Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>|\Zend\View\Model\ViewModel
	 */
	public function creerMachineAction(){
		$uneIp = $this->getIpFromUrl(true);
		if ($uneIp instanceof Response){
			//Redirection
			return $uneIp;
		}
		$unReseau = $uneIp->getReseau();

		$machineService = $this->getMachineService();
		$form = $this->getCreerMachinePourIpForm();
		$form->get('ipString')->setValue(long2ip($uneIp->getIp()));

		if($this->getRequest()->isPost()) {
			$form->setData($this->getRequest()->getPost());
			$form->setInputFilter(new MachinePourIpFilter());
			if($form->isValid()) {
				$ipService  = $this->getIpService();
				$uneMachine = $machineService->creerUneNouvelleMachinePourIp($form,$uneIp);
				$uneMachine->setCreateur($this->identity());
				//if ($machineService->isUnique($uneMachine)){
					//Enregistrement
					$machineService->enregistrerUneMachine($uneMachine);
					$ipService->enregistrerUneIp($uneIp);
					$message = $this->getTranslatorHelper()->translate('La nouvelle machine %s a été associée avec succès à l\'IP %s', 'iptrevise');
					$message = sprintf($message,$uneMachine->getLibelle(),long2Ip($uneIp->getIp()));
					$this->flashMessenger()->addSuccessMessage($message);
					return $this->redirect()->toRoute('ip',array('action'=>'consulter','ip'=>$uneIp->getId()));
				/*}else{
					//$array= $reservationIpForm->getMessages();
					$array['ip']['isUnique']=$this->getTranslatorHelper()->translate(
							'Cette adresse Ip est déjà déclaré dans ce réseau'
					);
					$form->setMessages($array);
				}*/
			} else {
				//Marche pas
				//die('invalide');
				$this->flashMessenger()->addErrorMessage($this->getTranslatorHelper()->translate('Les informations envoyées comportent des erreurs. Veuillez les rectifier puis les renvoyer !'));
				//return $this->redirect()->toRoute('reseau');
			}
		}
		$viewModel = new ViewModel(array(
				'form' 			=> $form,
				'uneIp'			=> $uneIp,
				'unReseau'		=> $unReseau,
		));
		return $viewModel;
	}

	/**
	 * Listing des Ips du réseau
	 *
	 * @return \Zend\View\Model\ViewModel
	 */
	public function indexAction() {
		$ipService = $this->getIpService();
    	$ips = $ipService->listerToutesLesIps();
    	return new ViewModel(array('ips' => $ips));
	}

	/**
	 * Cas d'utilisation : Consulter une IP d'un des réseaux
	 *
	 * @return Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>|\Zend\View\Model\ViewModel
	 */
	public function consulterAction()
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
     * Action de suppression d'une ip
	 * @return \Zend\Http\PhpEnvironment\Response|Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>|\Zend\View\Model\ViewModel
	 */
    public function supprimerAction(){

    	$uneIp = $this->getIpFromUrl(true);
    	if ($uneIp instanceof Response){
    		//Redirection
    		return $uneIp;
    	}
    	//Récupération directe du réseau
    	$unReseau = $uneIp->getReseau();
    	$uneMachine = $uneIp->getMachine();

    	$confirmation = (int) $this->params()->fromRoute('confirmation', 0);

    	if($confirmation == AbstractActionController::CONFIRMER){
    		$this->getIpService()->supprimerUneIp($uneIp);
    		$message = sprintf($this->getTranslatorHelper()->translate("Adresse IP %s supprimée avec succès", 'iptrevise'),long2ip($uneIp->getIp()));
    		$this->flashMessenger()->addSuccessMessage($message);
    	    	    if ($uneMachine instanceof Machine){
    			//S'il y avait une machine je retourne sur machine
    			return $this->redirect()->toRoute('machine',array('action'=>'consulter','machine'=>$uneMachine->getId()));
    		} else {
    			//Sinon je retourne sur le réseau
    			return $this->redirect()->toRoute('reseau',array('action'=>'consulter','reseau'=>$unReseau->getId()));
    		}
    	}elseif($confirmation == AbstractActionController::ANNULER){
    		$message = sprintf($this->getTranslatorHelper()->translate("Annulation demandée. L'adresse IP %s n'a pas été supprimée", 'iptrevise'),long2ip($uneIp->getIp()));
    		$this->flashMessenger()->addInfoMessage($message);
    	    if ($uneMachine instanceof Machine){
    			//S'il y avait une machine je retourne sur machine
    			return $this->redirect()->toRoute('machine',array('action'=>'consulter','machine'=>$uneMachine->getId()));
    		} else {
    			//Sinon je retourne sur le réseau
    			return $this->redirect()->toRoute('reseau',array('action'=>'consulter','reseau'=>$unReseau->getId()));
    		}
    	}

    	return new ViewModel(array(
    			'unReseau' => $unReseau,
    			'uneIp'	 => $uneIp,
    	));

    }

	/**
	 * Action de dissociation d'une IP à une Machine
	 *
	 */
	public function dissocierMachineAction(){
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
			return $this->redirect()->toRoute('ip',array('action'=>'consulter','ip'=>$uneIp->getId()));
		}
		//FIXME créer la vue dissocier machine depuis dissocier ip
		//FIXME vérifier le lien dissocierIP dans toutes les vues
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
	 *
	 * @param string $writable
	 * @param string $obligatoire
	 * @return number|boolean|Machine
	 */
	protected function getMachineFromUrl($writable = false,$obligatoire = true){
		$id = (int) $this->params()->fromRoute('machine', 0);
		//Si ce n'est pas obligatoire et qu'on a zéro, on retourne 0
		if ($id == 0 && ! $obligatoire){
			return 0;
		}
		//Sinon traitement standard
		if ($id == 0) {
			$this->flashMessenger()->addErrorMessage($this->getTranslatorHelper()->translate('Identifiant machine invalide', 'iptrevise'));
			return FALSE;
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
			return FALSE;
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
	/**
	 * getCreerMachinePourIpForm
	 *
	 * @return
	 */
	protected function getCreerMachinePourIpForm()
	{
		if (null === $this->machinePourIpForm) {
			$this->machinePourIpForm = $this->getServiceLocator()->get('MachinePourIpForm');
		}

		return $this->machinePourIpForm;
	}
}