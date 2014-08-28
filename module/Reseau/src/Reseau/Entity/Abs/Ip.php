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
use Zend\Db\Sql\Ddl\Column\Integer;
use Reseau\Entity\Abs\Reseau;

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
     * @var integer
     *
     * @ORM\Column(name="res_id", type="integer")
     */
    protected $reseauId;

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
	 * @return the $reseauId
	 */
	public function getReseauId() {
		return $this->reseauId;
	}

	/**
	 * @param number $reseauId
	 */
	public function setReseauId($reseauId) {
		$this->reseauId = $reseauId;
	}


}
