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
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Doctrine ORM implementation of Machine entity
 *
 */
abstract class Machine
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="mac_id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	protected $id;

	/**
	 * @var \Reseau\Entity\Table\Ip
	 * @ORM\OneToMany(targetEntity="Ip", mappedBy="machine",fetch="EXTRA_LAZY")
	 */
	protected $ips;

	/**
	 *
	 * @var User
	 * @ORM\ManyToOne(targetEntity="\CsnUser\Entity\User")
	 * @ORM\JoinColumn(name="usr_id", referencedColumnName="id")
	 */
	protected $createur;

    /**
     * @var string
     *
     * @ORM\Column(name="mac_lib", type="string", length=32, nullable=false, unique=false)
     */
    protected $libelle;

    /**
     * @var string
     *
     * @ORM\Column(name="mac_des", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var Integer
     *
     * @ORM\Column(name="mac_interface",type="smallint",nullable=true)
     */
    protected $interface;

    /**
     * @var string
     *
     * @ORM\Column(name="mac_type", type="string", length=32, nullable=true, unique=false)
     */
    protected $type;

    public function __construct(){
   		$this->ips = new ArrayCollection();
    }

	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return the $ips
	 */
	public function getIps() {
		return $this->ips;
	}

	/**
	 * @return the $createur
	 */
	public function getCreateur() {
		return $this->createur;
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
	 * @return the $masque
	 */
	public function getInterface() {
		return $this->interface;
	}

	/**
	 * @return the $type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param number $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @param \Reseau\Entity\Table\Ip $ips
	 */
	public function setIps($ips) {
		$this->ips = $ips;
	}

	/**
	 * @param \Reseau\Entity\Table\User $createur
	 */
	public function setCreateur($createur) {
		$this->createur = $createur;
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
	 * @param number $interface
	 */
	public function setInterface($interface) {
		$this->interface = $interface;
	}

	/**
	 * @param string $type
	 */
	public function setType($type) {
		$this->type = $type;
	}

	abstract public function getReseauCount();
	abstract public function getIpCount();
	abstract public function getUsername();

}
