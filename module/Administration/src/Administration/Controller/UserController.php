<?php
/**
 * 
 */
namespace Administration\Controller;

/**
 * 
 */
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Application\Entity\User;
use Zend\Paginator\Adapter\Iterator;
use Zend\Paginator\Paginator as ZendPaginator;
use Administration\Form\Element\ItemsPerPage;
use Administration\Form\Search;

/**
 * 
 * @author alexandre
 *
 */
class UserController extends AbstractActionController
{
    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
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
     * 
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function _getRepository(){
        return $this->_getEntityManager()->getRepository(User::class);
    }
    /**
     * Listing des utilisateurs
     * 
     * @todo to implement
     * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
     */
    public function indexAction()
    {
        //Initialisation de variables
        $dqlParams = array();
        $params = array();
        
        //Gestion des paramètres
        $itemsPerPage = $this->params()->fromRoute('itemsPerPage');
        $page         = $this->params()->fromRoute('page');
        $sort         = $this->params()->fromRoute('sort');
        $searchText   = $this->params()->fromQuery('search','');   

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
        $query = $this->_getEntityManager()->createQuery($dql,false);
        if (!empty($searchText)){
            $query->setParameters($params);
        }

        //Pagination
        $d2_paginator = new DoctrinePaginator($query);
        $d2_paginator_iter = $d2_paginator->getIterator(); // returns \ArrayIterator object
        $adapter = new Iterator($d2_paginator_iter);
        $zend_paginator = new ZendPaginator($adapter);
        $zend_paginator->setItemCountPerPage($itemsPerPage);
        $zend_paginator->setCurrentPageNumber($page);
        
        //Boutons du nombre d'items par page
        $ioSelect = new ItemsPerPage();
        $ioSelect->setValue($itemsPerPage);
        
        //Formulaire de recherche
        $searchForm = new Search();
        //Formulaire de recherche initialisé
        $searchForm->setData(['search' => $searchText]);

        //Lancement de la vue
        return new ViewModel(array(
            'searchForm'      => $searchForm,
            'searchText'      => $searchText,
            'ioSelect'        => $ioSelect,
            'paginator'       => $zend_paginator,
            'page'            => $zend_paginator->getCurrentPageNumber(),
            'itemsPerPage'    => $zend_paginator->getItemCountPerPage(),
            'sort'            => $sort,
            'viewIsAllowed'   => $this->isAllowed('userAdmin', 'view'),
            'editIsAllowed'   => $this->isAllowed('userAdmin', 'edit'),
            'deleteIsAllowed' => $this->isAllowed('userAdmin', 'delete'),
            'banIsAllowed'    => $this->isAllowed('userAdmin', 'ban'),
            'activatingIsAllowed' => $this->isAllowed('userAdmin', 'activating'),
        ));
    }
    /**
     * Retourne le tri pour la requête DQL
     * 
     * @FIXME Cette méthode ne respecte pas la séparation DQL / Controller
     * @param string $sort
     * @return string
     */
    private function _getOrderBy($sort){
        switch($sort){
            case 'InscriptionAsc':
                return 'e.inscription ASC';
            case 'InscriptionDesc':
                return 'e.inscription DESC';
            case 'LastVisiteAsc':
                return 'e.lastVisite ASC';
            case 'LastVisiteDesc':
                return 'e.lastVisite DESC';
            case 'DisplayNameAsc':
                return 'e.displayName ASC';
            case 'DisplayNameDesc':
                return 'e.displayName DESC';
            case 'EmailAsc':
                return 'e.email ASC';
            case 'EmailDesc':
                return 'e.email DESC';
            case 'UsernameAsc':
                return 'e.username ASC';
            case 'UsernameDesc':
                return 'e.username DESC';
            case 'IdDesc':
                return 'e.id DESC';
            default:
                return 'e.id ASC';
        }
        
    }
}

