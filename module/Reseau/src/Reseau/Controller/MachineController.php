<?php

namespace Reseau\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Reseau\Service\Factory\MachineService;

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
	 * The default action - show the home page
	 */
	public function indexAction() {
		$machineService = $this->getMachineService();
    	$machines = $machineService->listerToutesLesMachines();
    	return new ViewModel(array('machines' => $machines));
	}
	protected function getMachineService()
	{
		if (null === $this->machineService) {
			$this->machineService = $this->getServiceLocator()->get('MachineService');
		}

		return $this->machineService;
	}
}