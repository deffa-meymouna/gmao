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
use Reseau\Entity\Abs\Reseau   as ReseauEntity;
use Reseau\Form\ReservationIpForm;
use Reseau\Entity\Table\Ip;
use Reseau\Entity\Abs\Ip as IpEntity;
use Reseau\Entity\Abs\Machine;
use Reseau\Entity\Table\Machine as MachineTable;
use Reseau\Form\ReferencementIpForm;

class Ips
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
     * Liste toutes les ips d'un réseau depuis la vue
     * @return
     */
    public function rechercherLesIPDuReseauSelonId(ReseauEntity $unReseau){
    	$vue = $this->entityManager->getRepository('Reseau\Entity\View\Ip');
    	return $vue->findByReseauId($unReseau->getId());
    }
    /**
     * Liste toutes les ips d'un réseau depuis la vue
     * @return
     */
    public function rechercherLesIpSansMachineDuReseau(ReseauEntity $unReseau){
    	$vue = $this->entityManager->getRepository('Reseau\Entity\View\Ip');
    	return $vue->findBy(array('reseauId' => $unReseau->getId(),'machineId'=>null));
    }

	/**
	 * Retourne la première adresse IP disponible du réseau
	 * Retourne FALSE si le réseau n'a plus d'adresse disponible
	 *
	 * @param ReseauEntity $unReseau
	 * @return boolean|number
	 */
    public function rechercherLaPremiereIpDisponibleDuReseau(ReseauEntity $unReseau){
    	$vue = $this->entityManager->getRepository('Reseau\Entity\View\Ip');
    	$ips = $vue->findBy(
    			array('reseauId' => $unReseau->getId()),
    			array('ip' => 'ASC')
    	);
    	//Si toutes les IPs sont prises, je ne vais pas parcourir mon tableau !
    	if (count($ips) == $unReseau->getIpTheoriquementDisponible()){
    		return false;
    	}
    	//La première ip possible est l'adresse du réseau + 1
    	$resultat = $unReseau->getIp() + 1;
    	//Je boucle sur toutes les ips de la table à la recherche du premier trou
    	foreach ($ips as $ip){
    		if ($ip->getIp() > $resultat){
    			//on est dans un trou, on peut rendre la valeur
    			return $resultat;
    		}elseif ($ip->getIp() == $resultat){
    			$resultat++;
    			//Cette IP est déjà prise, testons la suivante
    		}else{
    			//L'adresse IP est inférieur à résultat, je passe à la suivante
    		}
    	}
    	return $resultat;
    }
	/**
	 * Création d'une ip depuis un formulaire de reservation et un réseau
	 *
	 * @param ReservationIpForm $form
	 * @param ReseauEntity $unReseau
     * @return \Reseau\Entity\Table\Ip
     */
    public function creerUneNouvelleIp(ReservationIpForm $form, ReseauEntity $unReseau){
    	$ip = new Ip();
    	$ip->setDescription($form->get('description')->getValue());
    	$ip->setReseau($unReseau);
    	$ip->setIpFromString($form->get('ip')->getValue());
    	$ip->setLibelle($form->get('libelle')->getValue());
    	$ip->setInterface($form->get('interface')->getValue());
    	$ip->setNat($form->get('nat')->getValue());
    	return $ip;
    }
    /**
     * Crée une IP et l'associe à une IP
     * @param ReservationIpForm $form
     * @param ReseauEntity $unReseau
     * @param Machine $machine
     * @return \Reseau\Entity\Table\Ip
     */
	public function creerUneNouvelleIpPourMachine(ReservationIpForm $form,ReseauEntity $unReseau, Machine $machine){
    	$nouvelleIp = $this->creerUneNouvelleIp($form, $unReseau);
    	$nouvelleIp->setMachine($machine);
    	return $nouvelleIp;
    }


    /**
     * Création d'une ip depuis un formulaire de référencement et un réseau
     *
     * @param ReferencementIpForm $form
     * @param MachineTable $uneMachine
     * @param ReseauEntity $unReseau
     * @return \Reseau\Entity\Table\Ip
     */
    public function referencerUneNouvelleIp(ReferencementIpForm $form, MachineTable $machine, ReseauEntity $unReseau){
    	$ip = new Ip();
    	$ip->setDescription($form->get('ip_description')->getValue());
    	$ip->setReseau($unReseau);
    	$ip->setIpFromString($form->get('ip')->getValue());
    	$ip->setLibelle($form->get('ip_libelle')->getValue());
    	$ip->setInterface(null);
    	$ip->setNat(false);
    	$ip->setMachine($machine);
    	return $ip;
    }
    /**
     * Retourne vraie si l'ip en paramètre n'existe pas encore dans le réseau donné
     *
     * @param IpEntity $ip
     * @param ReseauEntity $unReseau
     * @return boolean
     */
    public function isUnique(IpEntity $ip, ReseauEntity $unReseau){
    	$vue = $this->entityManager->getRepository('Reseau\Entity\View\Ip');
    	$ips = $vue->findBy(
    			array(
    				'reseauId' => $unReseau->getId(),
    				'ip' => $ip->getIp()
    			)
    	);
    	return count($ips) === 0;
    }
    /**
 	 * Enregistre une IP
 	 *
     * @param Ip $uneIp
     */
    public function enregistrerUneIp(Ip $uneIp){
    	$this->entityManager->persist($uneIp);
    	$this->entityManager->flush();
    }

    /**
     * Recherche une Ip selon son Id
     *
     * @param integer $id
     * @return Reseau\Entity\View\Ip
     */
    public function rechercherUneIpSelonId($id){
    	$ips = $this->entityManager->getRepository('Reseau\Entity\Table\Ip');
    	return $ips->find($id);
    }
    /**
     * Recherche une Ip selon son Id En mode Lecture seule
     *
     * @param integer $id
     * @return Reseau\Entity\View\Ip
     */
    public function rechercherUneIpSelonIdEnLectureSeule($id){
    	$ips = $this->entityManager->getRepository('Reseau\Entity\View\Ip');
    	return $ips->find($id);
    }

    /**
     * Supprime une Ip
     *
     * @param IpEntity $reseau
     */
    public function supprimerUneIp(IpEntity $ip){
    	$this->entityManager->remove($ip);
    	$this->entityManager->flush();
    }

    /**
     *
     */
    public function rechercherLesIPDUneMachine(Machine $machine){
    	$ips = $this->entityManager->getRepository('Reseau\Entity\View\Ip');
    	return $ips->findByMachineId($machine->getId());
    }
}
