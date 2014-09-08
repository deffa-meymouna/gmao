<?php
/**
 * Reseaux - Reseau Entity Manager to use with EntityManagerInterface
 *
 * @link https://github.com/Alexandre-T/gmao for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Alexandre Tranchant
 * @license https://github.com/Alexandre-T/gmao/blob/master/LICENSE MIT
 * @author Alexandre Tranchant <Alexandre.Tranchant@gmail.com>
 */

namespace Reseau\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Zend\Paginator\Paginator;
use Reseau\Entity\Abs\Reseau   as ReseauEntity;
use Reseau\Entity\Table\Machine as MachineTable;
use Reseau\Form\MachineForm;

class Machines
{
	/**
	 * @var EntityManager
	 */
	private $entityManager;
	/**
	 * Constructeur
	 *
	 * @param EntityManager $entityManager
	 */
	public function __construct(EntityManagerInterface $entityManager){
		$this->entityManager = $entityManager;
	}
    /**
     * Liste toutes les machines
     * @return
     */
    public function listerToutesLesMachines(Paginator $paginator = null){

    	$repository = $this->entityManager->getRepository('Reseau\Entity\Table\Machine');

    	if ($paginator){
    		//$adapter = new DoctrineAdapter(new ORMPaginator($repository->createQueryBuilder('Machine')));
    		//$paginator = new Paginator ($adapter);
    		//$paginator->setDefaultItemCountPerPage(10);
    		//$page = (int)$this->params()->fromQuery('page');
    		//if($page) $paginator->setCurrentPageNumber($page);
    		//$view->setVariable('paginator',$paginator);
    	}
    	/*$query = $this->entityManager->createQueryBuilder()
    		->from('Machine')
    		->innerJoin('Ips')
    		->addSelect('COUNT(Machine) as quantiteMachine')
    		->addGroupBy('Machine.id');*/
    	return $repository->findall();
    }
    /**
     * Service de création d'une Entité Machine à partir d'un formulaire valide
     * et préalablement filtré
     *
     * @param MachineForm $form
     * @return Machine
     */
    public function creerUneNouvelleMachine(MachineForm $form){
    	$nouvelleMachine = new MachineTable();
		$nouvelleMachine->setDescription($form->get('description')->getValue());
		$nouvelleMachine->setLibelle($form->get('libelle')->getValue());
		$nouvelleMachine->setInterface($form->get('interface')->getValue());
		$nouvelleMachine->setType($form->get('type')->getValue());
		return $nouvelleMachine;
    }
	/**
	 * Enregistre une Machine
	 *
	 * @param Reseau $unReseau
	 */
	public function enregistrerUneMachine(MachineTable $uneMachine){
		$this->entityManager->persist($uneMachine);
		$this->entityManager->flush();
	}
	/**
	 * Recherche un Reseau selon son Id
	 *
	 * @param integer $id
	 * @return Reseau\Entity\View\Reseau
	 */
	public function rechercherUnReseauSelonId($id){
		$reseaux = $this->entityManager->getRepository('Reseau\Entity\Table\Reseau');
		return $reseaux->find($id);
	}
	/**
	 * Recherche un Reseau selon son Id En mode Lecture seule
	 *
	 * @param integer $id
	 * @return Reseau\Entity\View\Reseau
	 */
	public function rechercherUnReseauSelonIdEnLectureSeule($id){
		$reseaux = $this->entityManager->getRepository('Reseau\Entity\View\Reseau');
		return $reseaux->find($id);
	}
	/**
	 * Supprime un reseau
	 *
	 * @param ReseauEntity $reseau
	 */
	public function supprimerUnReseau(ReseauEntity $reseau){
		$this->entityManager->remove($reseau);
		$this->entityManager->flush();
	}
}
