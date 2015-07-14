<?php
/**
 * Utilisateurs - Utilisateur Entity Service Manager to use with EntityManagerInterface
 *
 * @link https://github.com/Alexandre-T/gmao for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Alexandre Tranchant
 * @license https://github.com/Alexandre-T/zfc-blanche/blob/master/LICENSE MIT
 * @author Alexandre Tranchant <Alexandre.Tranchant@gmail.com>
 */
namespace Application\Service;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\User;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Zend\Paginator\Adapter\Iterator;
use Zend\Paginator\Paginator as ZendPaginator;

class Users
{
    
    const INSCRIPTION_ASC  = 'InscriptionAsc';
    const INSCRIPTION_DESC = 'InscriptionDesc';
    const LASTVISITE_ASC   = 'LastVisiteAsc';
    const LASTVISITE_DESC  = 'LastVisiteDesc';
    const DISPLAYNAME_ASC  = 'DisplayNameAsc';
    const DISPLAYNAME_DESC = 'DisplayNameDesc';
    const EMAIL_ASC        = 'EmailAsc';
    const EMAIL_DESC       = 'EmailDesc';
    const USERNAME_ASC     = 'UsernameAsc';
    const USERNAME_DESC    = 'UsernameDesc';
    const ID_ASC           = 'IdAsc';
    const ID_DESC          = 'IdDesc';
    
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
	public function activeUser(User $user){
	    $user->setActivation(new \DateTime("now"));
	    $user->setUpdated();	    
	}
	/**
	 * 
	 * @param string $searchText
	 * @param string $sort
	 * @return Zend\Paginator\Paginator
	 */
    public function searchUsers($searchText,$sort = self::ID_ASC){
        //Construction de la requête DQL
        $dql = 'SELECT e FROM ' . User::class . ' e ';
        if (!empty($searchText)){
            $dql .= ' WHERE ';
            if ((int)($searchText)){
                $dqlParams[] = ' e.id like :id ';
                $params['id'] = "%$searchText%";
            }
            $dqlParams[]= ' e.username like :username ';
            $dqlParams[]= ' e.displayName like :displayname ';
            $dqlParams[]= ' e.email like :email ';
            $params['username']    = "%$searchText%";
            $params['displayname'] = "%$searchText%";
            $params['email']       = "%$searchText%";
            $dql .= implode(' OR ', $dqlParams);
        }
        $dql.= ' ORDER BY '.  $this->_getOrderBy($sort);
        $query = $this->entityManager->createQuery($dql,false);
        if (!empty($searchText)){
            $query->setParameters($params);
        }
        $d2_paginator = new DoctrinePaginator($query);
        $d2_paginator_iter = $d2_paginator->getIterator(); // returns \ArrayIterator object
        $adapter = new Iterator($d2_paginator_iter);
        return new ZendPaginator($adapter);
        
    }
        
    /**
     * Service de création d'une Entité Machine à partir d'un formulaire valide
     * et préalablement filtré de type ReferencerIPForm
     * @todo to implement
     * @param MachineForm $form
     * @return Machine
     */
    /*public function referencerUnNouvelUtilisateur(ReferencementIpForm $form){
    	$nouvelleMachine = new MachineTable();
    	$nouvelleMachine->setDescription($form->get('mac_description')->getValue());
    	$nouvelleMachine->setLibelle($form->get('mac_libelle')->getValue());
    	$nouvelleMachine->setInterface($form->get('mac_interface')->getValue());
    	$nouvelleMachine->setType($form->get('mac_type')->getValue());
    	return $nouvelleMachine;
    }*/
	/**
	 * Enregistre une Machine
	 *
	 * @param User $unUser
	 */
	public function saveUser(User $unUser){
	    $unUser->setUpdated();
		$this->entityManager->persist($unUser);
		$this->entityManager->flush();
	}
	/**
	 * Recherche un Utilisateur selon son Id
	 *
	 * @param integer $id
	 * @return Application\Entity\User
	 */
	public function findUserById($id){
		return $this->entityManager->getRepository('Application\Entity\User')->find($id);
	}

	/**
	 * Supprime un utilisateur
	 * @FIXME To implement
	 * @param User $unUser
	 * @return void
	 */
	public function deleteUser(User $unUser){
		$this->entityManager->remove($unUser);
		$this->entityManager->flush();
	}

	/**
	 *
	 * @return \Doctrine\ORM\EntityManager
	 */
	protected function _getEntityManager()
	{
	    if (null === $this->em) {
	        $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
	    }
	    return $this->em;
	}
	
	/**
	 * Retourne le tri pour la requête DQL
	 *
	 * @param string $sort
	 * @return string
	 */
	private function _getOrderBy($sort){
	    switch($sort){
	        case self::INSCRIPTION_ASC:
	            return 'e.inscription ASC';
	        case self::INSCRIPTION_DESC:
	            return 'e.inscription DESC';
	        case self::LASTVISITE_ASC:
	            return 'e.lastVisite ASC';
	        case self::LASTVISITE_DESC:
	            return 'e.lastVisite DESC';
	        case self::DISPLAYNAME_ASC:
	            return 'e.displayName ASC';
	        case self::DISPLAYNAME_DESC:
	            return 'e.displayName DESC';
	        case self::EMAIL_ASC:
	            return 'e.email ASC';
	        case self::EMAIL_DESC:
	            return 'e.email DESC';
	        case self::USERNAME_ASC:
	            return 'e.username ASC';
	        case self::USERNAME_DESC:
	            return 'e.username DESC';
	        case self::ID_DESC:
	            return 'e.id DESC';
	        case self::ID_ASC:
	        default:
	            return 'e.id ASC';
	    }
	}
}