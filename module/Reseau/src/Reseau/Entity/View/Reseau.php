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
use Reseau\Entity\Abs\Reseau as ReseauAbstract;
//use Doctrine\Common\Collections\ArrayCollection;

/**
 * Doctrine ORM implementation of Reseau entity
 *
 * @ORM\Entity
 * @ORM\Table(name="ve_reseau_res")
 */
class Reseau extends ReseauAbstract
{

    /**
     * @var integer
     *
     * @ORM\Column(name="mac_quantite", type="integer")
     */
    protected $mac_quantite;

    /**
     * @var integer
     *
     * @ORM\Column(name="ip_quantite", type="integer")
     */
    protected $ip_quantite;

    /**
     * @var integer
     *
     * @ORM\Column(name="usr_id", type="integer")
     */
    protected $createurID;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_username", type="string")
     */
    protected $createurUsername;

    /**
     *
     */
	public function getQuantiteIp(){
		return $this->ip_quantite;
	}
	/**
	 *
	 * @return number
	 */
	public function getIpPourcentageOccupe(){
		return $this->ip_quantite / $this->getIpTheoriquementDisponible() * 100;
	}

	/**
	 *
	 * @return number
	 */
	public function getQuantiteMachine(){
		return $this->mac_quantite;
	}
	/* (non-PHPdoc)
	 * @see \Reseau\Entity\Abs\Reseau::getCreateurId()
	 */
	public function getCreateurId() {
		return $this->createurId;
	}

	/* (non-PHPdoc)
	 * @see \Reseau\Entity\Abs\Reseau::getCreateurUsername()
	 */
	public function getCreateurUsername() {
		return $this->createurUsername;
	}




}
