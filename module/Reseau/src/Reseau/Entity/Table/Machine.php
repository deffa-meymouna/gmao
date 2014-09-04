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
//use Doctrine\Common\Collections\ArrayCollection;

/**
 * Doctrine ORM implementation of Machine entity
 *
 * @ORM\Entity
 * @ORM\Table(name="`te_machine_mac`")
 */
class Machine
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
	 * @ORM\OneToMany(targetEntity="Ip", mappedBy="machine")
	 */
	protected $ips;

}
