<?php
/**
 * Reseaux - Reseau Entity Manager to use with EntityManagerInterface
 *
 * @link https://github.com/Alexandre-T/gmao for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Alexandre Tranchant
 * @license https://github.com/Alexandre-T/gmao/blob/master/LICENSE MIT
 * @author Alexandre Tranchant <Alexandre.Tranchant@gmail.com>
 */

namespace Reseau\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Reseau\Form\ReseauForm;
use Reseau\Entity\Table\Reseau as ReseauTable;
use Reseau\Entity\Abs\Reseau   as ReseauEntity;

class Ips
{
	/**
	 * @var EntityManager
	 */
	private $entityManager;
	/**
	 * Constructeur
	 *
	 * @param EntityManager $entityManager
	 */
	public function __construct(EntityManagerInterface $entityManager){
		$this->entityManager = $entityManager;
	}
    /**
     * Liste toutes les ips d'un réseau depuis la vue
     * @return
     */
    public function rechercherLesIPDuReseauSelonId($idReseau){
    	$vue = $this->entityManager->getRepository('Reseau\Entity\View\Ip');
    	return $vue->findByReseauId($idReseau);
    }
}
