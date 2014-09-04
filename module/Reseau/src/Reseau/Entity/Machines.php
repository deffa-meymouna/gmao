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
use Reseau\Form\ReseauForm;
use Reseau\Entity\Table\Reseau as ReseauTable;
use Reseau\Entity\Abs\Reseau   as ReseauEntity;

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
    public function listerToutesLesMachines(){
    	$vue = $this->entityManager->getRepository('Reseau\Entity\Table\Machine');
    	return $vue->findall();
    }
    /**
     * Service de création d'une Entité Machine à partir d'un formulaire valide
     * et préalablement filtré
     *
     * @param MachineForm $form
     * @return Machine
     */
    public function creerUnNouveauReseau(MachineForm $form){
    	$nouvelleMachine = new MachineTable();
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