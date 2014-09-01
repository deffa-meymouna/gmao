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
    	$ip->setReseauId($unReseau->getId());
    	$ip->setIpFromString($form->get('ip')->getValue());
    	$ip->setLibelle($form->get('libelle')->getValue());
    	return $ip;
    }
    /**
     * Retourne vraie si l'ip en paramètre n'existe pas encore dans le réseau donné
     *
     * @param IpEntity $ip
     * @param ReseauEntity $unReseau
     * @return boolean
     */
    public function isUnique(IpEntity $ip, ReseauEntity $unReseau = null){
    	if (null === $unReseau){
    		$idReseau = $ip->getReseauId();
    	}else{
    		$idReseau = $unReseau->getId();
    	}
    	$vue = $this->entityManager->getRepository('Reseau\Entity\Table\Ip');
    	$ips = $vue->findBy(
    			array(
    				'reseauId' => $idReseau,
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

}
