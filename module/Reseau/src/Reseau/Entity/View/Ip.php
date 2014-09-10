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
	 * @var string
	 *
	 * @ORM\Column(name="usr_username", type="text", nullable=false)
	 */
	protected $username;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="res_id", type="integer", nullable=false)
	 */
	protected $reseauId;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="res_lib", type="string", nullable=false)
	 */
	protected $reseauLibelle;

	/**
	 * @var long
	 *
	 * @ORM\Column(name="res_ip", type="bigint", nullable=false)
	 */
	protected $reseauIp;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="res_masque", type="smallint", nullable=false)
	 */
	protected $reseauMasque;

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
	 * @return the $username
	 */
	public function getCreateurUsername() {
		return $this->username;
	}

	/**
	 * @return the $reseauId
	 */
	public function getReseauId() {
		return $this->reseauId;
	}

	/**
	 * @return the $reseauLibelle
	 */
	public function getReseauLibelle() {
		return $this->reseauLibelle;
	}

	/**
	 * @return the $reseauCIDR
	 */
	public function getReseauCIDR() {
		return long2ip($this->reseauIp). '/'. $this->reseauMasque;
	}

}
