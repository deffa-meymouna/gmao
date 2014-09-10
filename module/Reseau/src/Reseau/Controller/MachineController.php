<?php

namespace Reseau\Controller;

use Zend\View\Model\ViewModel;
use Zend\Http\PhpEnvironment\Response;
use Reseau\Controller\AbstractActionController;
use Reseau\Service\Factory\MachineService;
use Reseau\Form\MachineFilter;


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
	 * @var MachineService
	 */
	protected $machineService;

	/**
	 *
	 */
	protected $machineForm;

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

		$ips = $ipService->rechercherLesIPDUneMachine($uneMachine);

		$viewModel = new ViewModel(array(
				'uneMachine' => $uneMachine,
				'createur' => $createur,
				'ips'	   => $ips
		));
		return $viewModel;
	}

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
	protected function getMachineService()
	{
		if (null === $this->machineService) {
			$this->machineService = $this->getServiceLocator()->get('MachineService');
		}

		return $this->machineService;
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
}