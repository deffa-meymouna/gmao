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
use BjyAuthorize\Service\Authorize;
use BjyAuthorize\Exception\UnAuthorizedException;
use Zend\Db\Sql\Ddl\Column\Date;
use Administration\Form\InputFilter\User as UserFilter;
use Zend\InputFilter\InputFilterInterface;
use ZfcUser\Mapper\UserInterface;
use Doctrine\ORM\EntityRepository;

class Users extends EntityRepository implements UserInterface
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
	 * @var Authorize
	 */
	private $authorize;
	/**
	 * 
	 * @var UserFilter
	 */
	private $inputFilter;

	/**
	 * Constructeur
     *
	 * @param EntityManagerInterface $entityManager
	 * @param Authorize $authorize
	 */
	public function __construct(EntityManagerInterface $entityManager, Authorize $authorize){
		$this->entityManager = $entityManager;
		$this->authorize = $authorize;
	}
	/**
	 * Active un utilisateur
	 * 
	 * @param User $user
	 * @throws UnAuthorizedException
	 */
	public function activeUser(User $user){
	    if (!$this->authorize->isAllowed('user','activating')){
	        //En dehors de l'admin, on n'a pas le droit d'activer, 
	        //sauf si on s'activait soi-même
	        //Mais on ne peut pas être identifié pour s'activer
	        //C'est pour cela que Guest à le droit d'activer
	        throw new UnAuthorizedException();
	    }
	    $user->setActivation(new \DateTime("now"));
	    $this->_saveUser($user);	    
	}
	/**
	 * Banni un utilisateur
	 * @param User $user
     * @throws UnAuthorizedException
	 */
	public function banUser(User $user){
	    if (!$this->authorize->isAllowed('user','ban')){	     
	        throw new UnAuthorizedException();
	    }
	    $user->setBannissement(new \DateTime("now"));
	    $this->_saveUser($user);  
	}
	/**
	 * Éditer un utilisateur
	 * @param User $user
	 * @throws UnAuthorizedException
	 */
	public function editUser(User $user){
	    if (!$this->authorize->isAllowed('user','edit')){
	        throw new UnAuthorizedException();
	    }	    
	    $this->_saveUser($user);
	}
	/**
	 * Créer un utilisateur
	 * @param User $user
	 * @throws UnAuthorizedException
	 */
	public function createUser(User $user){
	    if (!$this->authorize->isAllowed('user','create')){
	        throw new UnAuthorizedException();
	    }
	    $user->setInscription(new Date('now'));
	    $this->_saveUser($user);
	}
	/**
	 * «Débannir» un utilisateur
	 * @param User $user
	 * @throws UnAuthorizedException
	 */
	public function unbanUser(User $user){
		if (!$this->authorize->isAllowed('user','unban')){	     
	        throw new UnAuthorizedException();
	    }
	    $user->setBannissement(null);
	    $this->_saveUser($user);	    
	}
	/**
	 * 
	 * @param string $searchText
	 * @param string $sort
	 * @throws UnAuthorizedException
	 * @return Zend\Paginator\Paginator
	 */
    public function searchUsers($searchText,$sort = self::ID_ASC){
        if (!$this->authorize->isAllowed('user','search')){
            throw new UnAuthorizedException();
        }
        //Construction de la requête DQL
        $dql = 'SELECT e FROM ' . User::class . ' e ';
        if (!empty($searchText)){
            $dql .= ' WHERE ';
            if ((int)($searchText)){
                $dqlParams[] = ' e.id like :id ';
                $params['id'] = "%$searchText%";
            }
            $dqlParams[]= ' e.username like :username ';
            $dqlParams[]= ' e.displayName like :displayName ';
            $dqlParams[]= ' e.email like :email ';
            $params['username']    = "%$searchText%";
            $params['displayName'] = "%$searchText%";
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
	 * 
	 * @param User $unUser
	 * @throws UnAuthorizedException
	 * @return void
	 */
	public function deleteUser(User $unUser,$flush = true){
	    if (!$this->authorize->isAllowed('user','delete')){
	        throw new UnAuthorizedException();
	    }
		$this->entityManager->remove($unUser);
		if ($flush){
		  $this->entityManager->flush();
		}
	}

	/**
	 * Enregistre un User
	 * Cette fonction est volontairement protégée, car je ne veux pas qu'un malin
	 * chope un $user et le sauvegarde. Il pourrait alors le bannir.
	 *
	 * @param User $unUser
	 */
	protected function _saveUser(User $unUser,$flush = true){
	    $unUser->setUpdated();
		$this->entityManager->persist($unUser);
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
	
	/**
	 *
	 * @param InputFilterInterface $inputFilter
	 * @throws \Exception
	 */
	public function setInputFilter(InputFilterInterface $inputFilter)
	{
	    throw new \Exception("Not used");
	}
	/**
	 * 
	 * @return \Administration\Form\InputFilter\User
	 */
	public function getInputFilter()
	{
	    if (!$this->inputFilter) {
	        $inputFilter = new UserFilter($this);
	        $this->inputFilter = $inputFilter;
	    }
	
	    return $this->inputFilter;
	}
	
	/* (non-PHPdoc)
	 * @see \ZfcUser\Mapper\UserInterface::findByEmail()
	 */
	public function findByEmail($email)
	{
	    return $this->findBy(
	        ['email'=>$email]
	    );
	}
	
	/* (non-PHPdoc)
	 * @see \ZfcUser\Mapper\UserInterface::findById()
	 */
	public function findById($id)
	{
	    return $this->entityManager->find($id);
	}
	
	/* (non-PHPdoc)
	 * @see \ZfcUser\Mapper\UserInterface::findByUsername()
	 */
	public function findByUsername($username)
	{
	    return $this->entityManager->findBy(
	        ['username'=>$username]
	    );
	    	}
	
	/* (non-PHPdoc)
	 * @see \ZfcUser\Mapper\UserInterface::insert()
	 */
	public function insert($user)
	{
	   return $this->createUser($user);
	}
	
	/* (non-PHPdoc)
	 * @see \ZfcUser\Mapper\UserInterface::update()
	 */
	public function update($user)
	{
	    return $this->editUser($user);
	}
	
}