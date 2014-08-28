<?php
/**
 * Reseau - Reseau Entity to use with Doctrine
 *
 * @link https://github.com/Alexandre-T/gmao for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Alexandre Tranchant
 * @license https://github.com/Alexandre-T/gmao/blob/master/LICENSE MIT
 * @author Alexandre Tranchant <Alexandre.Tranchant@gmail.com>
 */

namespace Reseau\Entity\Abs;

use Doctrine\ORM\Mapping as ORM;
use Zend\Db\Sql\Ddl\Column\Integer;
use Reseau\Entity\Abs\Reseau;
//use Doctrine\Common\Collections\ArrayCollection;

/**
 * Doctrine ORM abstract implementation of Reseau entity
 *
 */
abstract class Reseau
{
    /**
     * @var integer
     *
     * @ORM\Column(name="res_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var \Reseau\Entity\Table\Ip
     * @ORM\OneToMany(targetEntity="Ip", mappedBy="reseau")
     */
    protected $ips;

    /**
     * @var string
     *
     * @ORM\Column(name="res_lib", type="string", length=32, nullable=false, unique=false)
     */
    protected $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="res_des", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="res_couleur", type="text", length=6, nullable=false)
     */
    protected $couleur = '000000'; //Couleur noire

    /**
     * @var Integer
     *
     * @ORM\Column(name="res_ip",type="bigint",nullable=false)
     */
    protected $ip;

    /**
     * @var Integer
     *
     * @ORM\Column(name="res_passerelle",type="bigint",nullable=false)
     */
    protected $passerelle;
    /**
     * @var Integer
     *
     * @ORM\Column(name="res_masque",type="smallint",nullable=false)
     */
    protected $masque;

	/**
	 * @return the $libelle
	 */
	public function getLibelle() {
		return $this->libelle;
	}

	/**
	 * @return the $description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @return the $couleur
	 */
	public function getCouleur() {
		return $this->couleur;
	}

	/**
	 * @param string $libelle
	 */
	public function setLibelle($libelle) {
		$this->libelle = $libelle;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @param string $couleur
	 */
	public function setCouleur($couleur) {
		$this->couleur = $couleur;
	}
	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param number $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return string the $ip
	 */
	public function getIp() {
		return $this->ip;
	}
	/**
	 * @return string the $ip
	 */
	public function getIpInString() {
		return long2ip($this->ip);
	}

	/**
	 * @return string the $ip/$mask
	 */
	public function getCIDR(){
		return $this->getIpInString().'/'.$this->masque;
	}

	/**
	 * @param \Zend\Db\Sql\Ddl\Column\Integer $ip
	 */
	public function setIp($ip) {
		$this->ip = $ip;
	}
	/**
	 * Prend une adresse ip au format 192.168.1.0
	 *
	 * @param String $chaineIp
	 */
	public function setIpFromString($chaineIp) {
		$this->ip = sprintf('%u',ip2long($chaineIp));
	}

	/**
	 * @return the $passerelle
	 */
	public function getPasserelle() {
		return long2ip($this->passerelle);
	}

	/**
	 * @param \Zend\Db\Sql\Ddl\Column\Integer $passerelle
	 */
	public function setPasserelle($passerelle) {
		$this->passerelle = $passerelle;
	}
	/**
	 * @param string $passerelle
	 */
	public function setPasserelleFromString($passerelle) {
		$this->passerelle = sprintf('%u',ip2long($passerelle));
	}

	/**
	 * @return the $masque
	 */
	public function getMasque() {
		return long2ip(pow(2,32) - pow(2,32 - $this->masque));
	}

	/**
	 * @param \Zend\Db\Sql\Ddl\Column\Integer $masque
	 */
	public function setMasque($masque) {
		$this->masque = $masque;
	}

	/**
	 * @return le nombre d'adresse IP théoriquement disponible dans le sous-reseau
	 */
	public function getIpTheoriquementDisponible(){
		return pow(2,32 - $this->masque) - 2;
	}
	/**
	 *
	 */
	public function getBroadcast(){
		return long2ip($this->ip + pow(2,32-$this->masque)-1);
	}
	/**
	 * @return the $ips
	 */
	public function getIps() {
		return $this->ips;
	}

	/**
	 * @param \Reseau\Entity\Table\Ip $ips
	 */
	public function setIps($ips) {
		$this->ips = $ips;
	}

}
