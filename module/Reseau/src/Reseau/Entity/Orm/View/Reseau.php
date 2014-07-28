<?php
/**
 * Reseau - Reseau Entity to use with Doctrine
 *
 * @link https://github.com/Alexandre-T/gmao for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Alexandre Tranchant
 * @license https://github.com/Alexandre-T/gmao/blob/master/LICENSE MIT
 * @author Alexandre Tranchant <Alexandre.Tranchant@gmail.com>
 */

namespace Reseau\Entity\Orm\View;

use Doctrine\ORM\Mapping as ORM;
use Reseau\Entity\Orm\Table\Reseau as TableReseau;
//use Doctrine\Common\Collections\ArrayCollection;

/**
 * Doctrine ORM implementation of Reseau entity
 *
 * @ORM\Entity
 * @ORM\Table(name="`ve_reseau_res`")
 */
class Reseau extends TableReseau
{

    /**
     * @var string
     *
     * @ORM\Column(name="mip_quantite", type="integer")
     */
    protected $mip_quantite;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_quantite", type="integer")
     */
    protected $ip_quantite;

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

}
