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
        //Gestion des paramètres
        $itemsPerPage = $this->params()->fromRoute('itemsPerPage');
        $page         = $this->params()->fromRoute('page');
        $sort         = $this->params()->fromRoute('sort');        

        //Requête DQL
        $dql = 'SELECT e FROM ' . User::class . ' e ORDER BY '.  $this->_getOrderBy($sort);
        $query = $this->_getEntityManager()->createQuery($dql,false);

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

        //Lancement de la vue
        return new ViewModel(array(
            'ioSelect'        => $ioSelect,
            'paginator'       => $zend_paginator,
            'page'            => $zend_paginator->getCurrentPageNumber(),
            'itemsPerPage'    => $zend_paginator->getItemCountPerPage(),
            'sort'            => $sort,
            'viewIsAllowed'   => $this->isAllowed('userAdmin', 'view'),
            'editIsAllowed'   => $this->isAllowed('userAdmin', 'edit'),
            'deleteIsAllowed' => $this->isAllowed('userAdmin', 'delete'),
        ));
    }
    /**
     * Retourne le tri pour la requête DQL
     * 
     * @FIXME Cette méthode ne respecte pas la séparation DQL / Controller
     * @param unknown $sort
     * @return string
     */
    private function _getOrderBy($sort){
        switch($sort){
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

