<?php
/**
 * Ip - Ip Entity to use with Doctrine
 *
 * @link https://github.com/Alexandre-T/gmao for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Alexandre Tranchant
 * @license https://github.com/Alexandre-T/gmao/blob/master/LICENSE MIT
 * @author Alexandre Tranchant <Alexandre.Tranchant@gmail.com>
 */

namespace Reseau\Entity\Abs;

use Doctrine\ORM\Mapping as ORM;
use Reseau\Entity\Abs\Reseau;
use Reseau\Entity\Abs\Machine;

/**
 * Doctrine ORM abstract implementation of Reseau entity
 *
 */
abstract class Ip
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ip_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_lib", type="string", length=32, nullable=false, unique=false)
     */
    protected $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_des", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="ip_interface", type="smallint", nullable=true)
     */
    protected $interface;
    /**
     * @var boolean
     *
     * @ORM\Column(name="ip_nat", type="boolean", nullable=false)
     */
    protected $nat;

    /**
     * @var Long
     *
     * @ORM\Column(name="ip_adresse",type="bigint",nullable=false)
     */
    protected $ip;

	/**
	 * @return the $libelle
	 */
	public function getLibelle() {
		return $this->libelle;
	}

	/**
	 * @return the $interface
	 */
	public function getInterface() {
		return $this->interface;
	}

	/**
	 * @return the $nat
	 */
	public function getNat() {
		return $this->nat;
	}

	/**
	 * @return the $description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $libelle
	 */
	public function setLibelle($libelle) {
		$this->libelle = $libelle;
	}

	/**
	 * @param number $interface
	 */
	public function setInterface($interface) {
		$this->interface = $interface;
	}

	/**
	 * @param boolean $nat
	 */
	public function setNat($nat) {
		$this->nat = $nat;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
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
	 * @return long the $ip
	 */
	public function getIp() {
		return $this->ip;
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
	 * @alias getNat()
	 * @return boolean true if is nated
	 */
	public function isNat() {
		return $this->nat;
	}

	/**
	 *
	 * @return boolean True si l'adresse IP est associée à une machine
	 */
	public function hasMachine(){
		return !empty($this->getMachineId());
	}


	/**
	 * @return integer the $reseauId
	 */
	abstract public function getReseauId();

	/**
	 * @return string the $reseauLibelle
	 */
	abstract public function getReseauLibelle();

	/**
	 * @return string the $reseau CIDR
	 */
	abstract public function getReseauCIDR();

	/**
	 * @return the $machineId
	 */
	abstract public function getMachineId();

	/**
	 * @return the $machineLibelle
	 */
	abstract public function getMachineLibelle();

	/**
	 * @return the $machineDescription
	 */
	abstract public function getMachineDescription();

	/**
	 * @return the $username
	 */
	abstract public function getCreateurUsername();

	/**
	 * @return integer the id of createur
	 */
	abstract public function getCreateurId();
}
