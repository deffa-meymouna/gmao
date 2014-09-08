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
use Reseau\Entity\Abs\Machine as MachineAbs;

/**
 * Doctrine ORM implementation of Machine entity
 *
 * @ORM\Entity
 * @ORM\Table(name="`ve_machine_mac`")
 */
class Machine extends MachineAbs
{
	/**
	 * @var Integer
	 *
	 * @ORM\Column(name="res_count",type="integer",nullable=true)
	 */
	protected $reseauQuantite;

	/**
	 * @var Integer
	 *
	 * @ORM\Column(name="ip_count",type="integer",nullable=true)
	 */
	protected $ipQuantite;
    /**
     * @var string
     *
     * @ORM\Column(name="usr_username", type="string", length=32, nullable=false, unique=false)
     */
	protected $username;

	public function getReseauCount(){
		return $this->reseauQuantite;
	}
	public function getIpCount(){
		return $this->ipQuantite;
	}
	public function getUsername(){
		return $this->username;
	}

}
