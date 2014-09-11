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
use Reseau\Entity\Abs\Machine as MachineAbs;

/**
 * Doctrine ORM implementation of Machine entity
 *
 * @ORM\Entity
 * @ORM\Table(name="`te_machine_mac`")
 */
class Machine extends MachineAbs
{
	public function getReseauCount(){
		$reseau = array();
		//@todo add a warning information for optimization !
		foreach($this->getIps() as $ip){
			$reseauId = $ip->getReseauId();
			if (null != $reseauId && !in_array($reseauId,$reseau)){
				$reseau[]=$reseauId;
			}
		}
		return count($reseau);
	}
	public function getIpCount(){
		//add a debug information for optimization !
		return $this->getIps()->count();
	}
	public function getCreateurUsername(){
		return $this->getCreateur()->getUsername();
	}
}
