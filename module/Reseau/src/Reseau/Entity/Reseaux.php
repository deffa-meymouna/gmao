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

class Reseaux
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
     * Liste tous les réseaux depuis la vue
     * @return
     */
    public function listerTousLesReseaux(){
    	$vue = $this->entityManager->getRepository('Reseau\Entity\View\Reseau');
    	return $vue->findall();
    }
    /**
     * Service de création d'une Entité Réseau à partir d'un formulaire valide
     * et préalablement filtré
     *
     * @param ReseauForm $form
     * @return Reseau
     */
    public function creerUnNouveauReseau(ReseauForm $form){
    	$nouveauReseau = new ReseauTable();
		$nouveauReseau->setCouleur(substr($form->get('couleur')->getValue(),1));
		$nouveauReseau->setDescription($form->get('description')->getValue());
		$nouveauReseau->setIpFromString($form->get('ip')->getValue());
		$nouveauReseau->setLibelle($form->get('libelle')->getValue());
		$nouveauReseau->setMasque($form->get('masque')->getValue());
		$nouveauReseau->setPasserelleFromString($form->get('passerelle')->getValue());
		return $nouveauReseau;
    }
	/**
	 * Enregistre un Reseau
	 *
	 * @param Reseau $unReseau
	 */
	public function enregistrerUnReseau(ReseauTable $unReseau){
		$this->entityManager->persist($unReseau);
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
	 * Supprime un reseau
	 *
	 * @param ReseauEntity $reseau
	 */
	public function supprimerUnReseau(ReseauEntity $reseau){
		$this->entityManager->remove($reseau);
		$this->entityManager->flush();
	}
}
