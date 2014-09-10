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
use Reseau\Entity\Abs\Reseau as ReseauAbstract;
use CsnUser\Entity\User;

//use Doctrine\Common\Collections\ArrayCollection;

/**
 * Doctrine ORM implementation of Reseau entity
 *
 * @ORM\Entity
 * @ORM\Table(name="`te_reseau_res`")
 */
class Reseau extends ReseauAbstract
{
	/**
	 *
	 * @var User
	 * @ORM\ManyToOne(targetEntity="\CsnUser\Entity\User")
	 * @ORM\JoinColumn(name="usr_id", referencedColumnName="id")
	 */
	protected $createur;

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
	/* (non-PHPdoc)
	 * @see \Reseau\Entity\Abs\Reseau::getCreateurId()
	 */
	public function getCreateurId() {
		return $this->getCreateur()->getId();
	}

	/* (non-PHPdoc)
	 * @see \Reseau\Entity\Abs\Reseau::getCreateurUsername()
	 */
	public function getCreateurUsername() {
		return $this->getCreateur()->getUsername();
	}



}
