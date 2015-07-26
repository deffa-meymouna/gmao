<?php
/**
 * Utilisateurs - Utilisateur Entity Service Manager to use with EntityManagerInterface
 *
 * @link https://github.com/Alexandre-T/gmao for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Alexandre Tranchant
 * @license https://github.com/Alexandre-T/zfc-blanche/blob/master/LICENSE MIT
 * @author Alexandre Tranchant <Alexandre.Tranchant@gmail.com>
 */
namespace Administration\Service;
use Doctrine\ORM\EntityManagerInterface;
use Administration\Entity\User;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Zend\Paginator\Adapter\Iterator;
use Zend\Paginator\Paginator as ZendPaginator;
use BjyAuthorize\Service\Authorize;
use BjyAuthorize\Exception\UnAuthorizedException;
use Zend\Db\Sql\Ddl\Column\Date;
use ZfcUser\Mapper\UserInterface;
use ZfcUserDoctrineORM\Mapper\User as UserMapper;
use ZfcUserDoctrineORM\Options\ModuleOptions;
use Administration\Entity\Role;

class Roles extends UserMapper implements UserInterface
{
    
    const ROLEID_ASC  = 'RoleIdAsc';
    const ROLE_DESC = 'RoleDesc';
    const ID_ASC           = 'IdAsc';
    const ID_DESC          = 'IdDesc';
    
	/**
	 * @var EntityManager
	 */
	private $entityManager;
	/**
	 * @var Authorize
	 */
	private $authorize;
	/**
	 * 
	 * @var RoleFilter
	 */
	private $inputFilter;

	/**
	 * Constructeur
     *
	 * @param EntityManagerInterface $entityManager
	 * @param Authorize $authorize
	 */
	public function __construct(EntityManagerInterface $entityManager, Authorize $authorize, ModuleOptions $options){
		$this->entityManager = $entityManager;
		$this->authorize = $authorize;
		parent::__construct($entityManager, $options);
	}
	/**
	 * Éditer un rôle
	 * @param Role $role
	 * @throws UnAuthorizedException
	 */
	public function editRole(Role $user){
	    if (!$this->authorize->isAllowed('role','edit')){
	        throw new UnAuthorizedException();
	    }	    
	    $this->_saveUser($user);
	}
	/**
	 * 
	 * @param string $searchText
	 * @param string $sort
	 * @throws UnAuthorizedException
	 * @return Zend\Paginator\Paginator
	 */
    public function searchRoles($searchText,$sort = self::ID_ASC){
        if (!$this->authorize->isAllowed('user','search')){
            throw new UnAuthorizedException();
        }
        //@FIXME Refaire la requête pour compter les utilisateurs disposants de ce rôle
        //Construction de la requête DQL
        $dql = 'SELECT e FROM ' . Role::class . ' e ';
        if (!empty($searchText)){
            $dql .= ' WHERE ';
            if ((int)($searchText)){
                $dqlParams[] = ' e.id like :id ';
                $params['id'] = "%$searchText%";
            }
            $dqlParams[]= ' e.roleId like :roleId ';
            $params['roleId']    = "%$searchText%";
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
	 * Recherche un Role selon son Id
	 *
	 * @param integer $id
	 * @return Administration\Entity\Role
	 */
	public function findRoleById($id){
		return $this->entityManager->getRepository('Administration\Entity\Role')->find($id);
	}

	/**
	 * Supprime un rôle
	 * 
	 * @param Role $unRole
	 * @throws UnAuthorizedException
	 * @throws RoleUsedException
	 * @return void
	 */
	public function deleteRole(Role $unRole,$flush = true){
	    //@FIXME vérifier que le rôle est supprimable
	    if (!$this->authorize->isAllowed('role','delete')){
	        throw new UnAuthorizedException();
	    }
		$this->entityManager->remove($unRole);
		if ($flush){
		  $this->entityManager->flush();
		}
	}

	/**
	 * Enregistre un Role
	 * Cette fonction est volontairement protégée, car je ne veux pas qu'un malin
	 * chope un $role et le sauvegarde. Il pourrait alors le bannir.
	 *
	 * @param User $unRole
	 */
	protected function _saveUser(Role $unRole,$flush = true){
	    $unRole->setUpdated();
		$this->entityManager->persist($unRole);
		if ($flush){
		    $this->entityManager->flush();
		}
	}
	/**
	 *
	 * @return \Doctrine\ORM\EntityManager
	 */
	protected function _getEntityManager()
	{
	    if (null === $this->entityManager) {
	        $this->entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
	    }
	    return $this->entityManager;
	}
	
	/**
	 * Retourne le tri pour la requête DQL
	 *
	 * @param string $sort
	 * @return string
	 */
	private function _getOrderBy($sort){
	    switch($sort){
	        case self::ROLEID_ASC:
	            return 'e.displayName ASC';
	        case self::ROLE_DESC:
	            return 'e.displayName DESC';
	        case self::ID_DESC:
	            return 'e.id DESC';
	        case self::ID_ASC:
	        default:
	            return 'e.id ASC';
	    }
	}
	
	/* (non-PHPdoc)
	 * @see \ZfcUser\Mapper\UserInterface::update()
	 */
	public function update($role)
	{
	    return $this->editRole($role);
	}
	
}