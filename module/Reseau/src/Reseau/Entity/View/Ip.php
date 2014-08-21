<?php
/**
 * Reseau - Reseau Entity to use with Doctrine
 *
 * @link https://github.com/Alexandre-T/gmao for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Alexandre Tranchant
 * @license https://github.com/Alexandre-T/gmao/blob/master/LICENSE MIT
 * @author Alexandre Tranchant <Alexandre.Tranchant@gmail.com>
 */

namespace Reseau\Entity\View;

use Doctrine\ORM\Mapping as ORM;
use Reseau\Entity\Abs\Ip as IpAbstract;

/**
 * Doctrine ORM implementation of Reseau entity
 *
 * @ORM\Entity
 * @ORM\Table(name="`ve_ip_ip`")
 */
class Ip extends IpAbstract
{
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
	 * @var string
	 *
	 * @ORM\Column(name="mac_id", type="integer", nullable=true)
	 */
	protected $machineId;
	/**
	 * @var string
	 *
	 * @ORM\Column(name="mac_lib", type="string", length=32, nullable=true)
	 */
	protected $machineLibelle;
	/**
	 * @var string
	 *
	 * @ORM\Column(name="mac_des", type="text", nullable=true)
	 */
	protected $machineDescription;
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
	 * @return the $machineId
	 */
	public function getMachineId() {
		return $this->machineId;
	}

	/**
	 * @return the $machineLibelle
	 */
	public function getMachineLibelle() {
		return $this->machineLibelle;
	}

	/**
	 * @return the $machineDescription
	 */
	public function getMachineDescription() {
		return $this->machineDescription;
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
	 * @param string $machineId
	 */
	public function setMachineId($machineId) {
		$this->machineId = $machineId;
	}

	/**
	 * @param string $machineLibelle
	 */
	public function setMachineLibelle($machineLibelle) {
		$this->machineLibelle = $machineLibelle;
	}

	/**
	 * @param string $machineDescription
	 */
	public function setMachineDescription($machineDescription) {
		$this->machineDescription = $machineDescription;
	}





}
