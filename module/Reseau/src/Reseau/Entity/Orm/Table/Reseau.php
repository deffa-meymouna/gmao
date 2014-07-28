<?php
/**
 * Reseau - Reseau Entity to use with Doctrine
 *
 * @link https://github.com/Alexandre-T/gmao for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Alexandre Tranchant
 * @license https://github.com/Alexandre-T/gmao/blob/master/LICENSE MIT
 * @author Alexandre Tranchant <Alexandre.Tranchant@gmail.com>
 */

namespace Reseau\Entity\Orm\Table;

use Doctrine\ORM\Mapping as ORM;
use Zend\Db\Sql\Ddl\Column\Integer;
//use Doctrine\Common\Collections\ArrayCollection;

/**
 * Doctrine ORM implementation of Reseau entity
 *
 * @ORM\Entity
 * @ORM\Table(name="`te_reseau_res`")
 */
class Reseau
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
     * Construct
     *
     * Construct some referenced columns arrays
     */
    public function __construct()
    {

    }

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
		return long2ip($this->ip);
	}
	/**
	 * @return string the $ip/$mask
	 */
	public function getCIDR(){
		return $this->getIp().'/'.$this->masque;
	}

	/**
	 * @param \Zend\Db\Sql\Ddl\Column\Integer $ip
	 */
	public function setIp($ip) {
		$this->ip = $ip;
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
}
