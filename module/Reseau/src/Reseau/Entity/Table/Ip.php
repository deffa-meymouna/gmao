<?php
/**
 * Reseau - Reseau Entity to use with Doctrine
 *
 * @link https://github.com/Alexandre-T/gmao for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Alexandre Tranchant
 * @license https://github.com/Alexandre-T/gmao/blob/master/LICENSE MIT
 * @author Alexandre Tranchant <Alexandre.Tranchant@gmail.com>
 */

namespace Reseau\Entity\Table;

use Doctrine\ORM\Mapping as ORM;
use Reseau\Entity\Abs\Ip as IpAbstract;
use Reseau\Entity\Abs\Machine as MachineAbstract;
use Reseau\Entity\Abs\Reseau as ReseauAbstract;
use CsnUser\Entity\User;

/**
 * Doctrine ORM implementation of Reseau entity
 *
 * @ORM\Entity
 * @ORM\Table(name="`te_ip_ip`")
 */
class Ip extends IpAbstract
{

	/**
	 *
	 * @var User
	 * @ORM\ManyToOne(targetEntity="\CsnUser\Entity\User")
	 * @ORM\JoinColumn(name="usr_id", referencedColumnName="id")
	 */
	protected $createur;

	/**
	 *
	 * @var Machine
	 * @ORM\ManyToOne(targetEntity="\Reseau\Entity\Table\Machine",inversedBy="ips",cascade={"persist"})
	 * @ORM\JoinColumn(name="mac_id", referencedColumnName="mac_id")
	 */
	protected $machine;

	/**
	 *
	 * @var Reseau
	 * @ORM\ManyToOne(targetEntity="\Reseau\Entity\Table\Reseau",inversedBy="ips")
	 * @ORM\JoinColumn(name="res_id", referencedColumnName="res_id")
	 */
	protected $reseau;

	/**
	 * @return the $createur
	 */
	public function getCreateur() {
		return $this->createur;
	}

	/**
	 * @param User $createur
	 */
	public function setCreateur(User $createur) {
		$this->createur = $createur;
	}

	/**
	 * @return the $machine
	 */
	public function getMachine() {
		return $this->machine;
	}

	/**
	 * @param \Reseau\Entity\Abs\Machine $machine
	 */
	public function setMachine(MachineAbstract $machine = null) {
		$this->machine = $machine;
	}

	/**
	 * @return the $reseau
	 */
	public function getReseau() {
		return $this->reseau;
	}

	/**
	 * @param \Reseau\Entity\Abs\Reseau $reseau
	 */
	public function setReseau(ReseauAbstract $reseau) {
		$this->reseau = $reseau;
	}

	/* (non-PHPdoc)
	 * @see \Reseau\Entity\Abs\Ip::getMachineDescription()
	 */
	public function getMachineDescription() {
		//@todo add a notice for optimisation
		if ($this->getMachine() instanceof MachineAbstract){
			return $this->getMachine()->getId();
		}
	}

	/* (non-PHPdoc)
	 * @see \Reseau\Entity\Abs\Ip::getMachineId()
	 */
	public function getMachineId() {
		if ($this->getMachine() instanceof MachineAbstract){
			return $this->getMachine->getId();
		}
	}

	/* (non-PHPdoc)
	 * @see \Reseau\Entity\Abs\Ip::getMachineLibelle()
	 */
	public function getMachineLibelle() {
		if ($this->getMachine() instanceof MachineAbstract){
			return $this->getMachine()->getLibelle();
		}
	}

	/* (non-PHPdoc)
	 * @see \Reseau\Entity\Abs\Ip::getCreateurId()
	*/
	public function getCreateurId() {
		return $this->getCreateur()->getId();
	}
	/* (non-PHPdoc)
	 * @see \Reseau\Entity\Abs\Ip::getUsername()
	 */
	public function getUsername() {
		return $this->getCreateur()->getUsername();
	}
	/* (non-PHPdoc)
	 * @see \Reseau\Entity\Abs\Ip::getCreateurUsername()
	 */
	public function getCreateurUsername() {
		return $this->getCreateur()->getUsername();
	}

	/* (non-PHPdoc)
	 * @see \Reseau\Entity\Abs\Ip::getReseauCIDR()
	 */
	public function getReseauCIDR() {
		return $this->getReseau()->getCIDR();
	}

	/* (non-PHPdoc)
	 * @see \Reseau\Entity\Abs\Ip::getReseauId()
	 */
	public function getReseauId() {
		return $this->getReseau()->getId();
	}

	/* (non-PHPdoc)
	 * @see \Reseau\Entity\Abs\Ip::getReseauLibelle()
	 */
	public function getReseauLibelle() {
		return $this->getReseau()->getLibelle();
	}


}
