<?php

namespace Administration\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Administration\Entity\Role;
use Administration\Form\Search;
use Zend\Http\Response;

class RoleController extends AbstractActionController
{
    const NO_RESPONSE = 0;
    const CANCEL      = 1;
    const CONFIRM     = 2;
    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    /**
     *
     * @var integer
     */
    protected $itemsPerPageDefault;
    /**
     *
     * @var \Administration\Form\RoleForm
     */
    protected $roleForm;
    /**
     *
     * @var Administration\Service\Roles
     */
    protected $rolesService;
    
    /**
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function _getRepository(){
        return $this->_getEntityManager()->getRepository(Role::class);
    }
    /**
     * Consulter un Rôle
     *
     * @return \Zend\Http\Response|\Zend\View\Model\ViewModel
     */
    public function viewAction(){
        $role = $this->_getRole();
        if ($role instanceof Response){
            //Redirection
            return $role;
        }
        return new ViewModel(array(
            'role'    => $role,
            'editIsAllowed' => $this->isAllowed('role', 'edit'),
            'banIsAllowed' => $this->isAllowed('role', 'ban'),
            'unbanIsAllowed' => $this->isAllowed('role', 'unban'),
        ));
    }
    /**
     * Editer un Rôle
     *
     * @return \Zend\Http\Response|\Zend\View\Model\ViewModel
     */
    public function editAction(){
        $role = $this->_getRole();
        if ($role instanceof Response){
            //Redirection
            return $role;
        }
        //Création du formulaire
        $form  = $this->_getForm($role->getId());
        $form->bind($role);
        $form->get('submit')->setAttribute('value', 'Edit');
    
        //Traitement de la requête
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                //Il faudrait passer par le service utilisateur
                $this->_getRolesService()->editRole($role);
                $message = _("Role %s was updated succesfully.");
                $message = sprintf($this->_getTranslatorHelper()->translate($message),$role->getRoleId());
                $this->flashMessenger()->addSuccessMessage($message);
                // Redirect to edited role in view mode
                return $this->redirect()->toRoute('zfcadmin/role',['action'=>'view','role'=>$role->getId()]);
            }
        }
    
        return new ViewModel(array(
            'id' => $role->getId(),
            'form' => $form,
        ));
    }
    /**
     * Supprimer un utilisateur
     *
     * @return \Zend\Http\Response|\Zend\View\Model\ViewModel
     */
    public function deleteAction(){
        $role = $this->_getRole();
        if ($role instanceof Response){
            //Redirection
            return $role;
        }
        $confirmation = $this->params()->fromRoute('confirmation', self::NO_RESPONSE);
        if($confirmation == self::CONFIRM){
            $this->_getRolesService()->deleteRole($role);
            $message = _("Role %s was updated succesfully.");
            $message = sprintf($this->_getTranslatorHelper()->translate($message),$role->getRoleId());
            $this->flashMessenger()->addSuccessMessage($message);
            return $this->redirect()->toRoute('zfcadmin/role',['action'=>'list']);
        }elseif($confirmation == self::CANCEL){
            $message = sprintf($this->_getTranslatorHelper()->translate("Cancellation ! The role %s was not deleted."),$role->getRoleId());
            $this->flashMessenger()->addInfoMessage($message);
            return $this->redirect()->toRoute('zfcadmin/role',['action'=>'view','role'=>$role->getId()]);
        }
        return new ViewModel(array(
            'role'    => $role,
            'confirm' => self::CONFIRM,
            'cancel'  => self::CANCEL,
        ));
    }
    /**
     * Lister les rôle
     *
     * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
     */
    public function listAction()
    {
        //Initialisation de variables
        $dqlParams = array();
        $params = array();
    
        //Gestion des paramètres
        $itemsPerPage = $this->params()->fromRoute('itemsPerPage',$this->_getItemsPerPageByDefault());
        $page         = $this->params()->fromRoute('page');
        $sort         = $this->params()->fromRoute('sort');
        $searchText   = $this->params()->fromQuery('search','');
    
        //Pagination
        $zend_paginator = $this->_getRolesService()->searchRoles($searchText,$sort);
        $zend_paginator->setItemCountPerPage($itemsPerPage);
        $zend_paginator->setCurrentPageNumber($page);
    
        //Boutons du nombre d'items par page
        $ioSelect = $this->serviceLocator->get('items_per_page_form');
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
            'createIsAllowed'   => $this->isAllowed('role', 'create'),
            'viewIsAllowed'   => $this->isAllowed('role', 'view'),
            'editIsAllowed'   => $this->isAllowed('role', 'edit'),
            'deleteIsAllowed' => $this->isAllowed('role', 'delete'),
        ));
    }
    
    /**
     *
     * @return Role|Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>
     */
    protected function _getRole(){
        $id = (int) $this->params()->fromRoute('role', 0);
        if ($id == 0) {
            $this->flashMessenger()->addErrorMessage($this->_getTranslatorHelper()->translate('Invalid role id'));
            return $this->redirect()->toRoute('zfcadmin/role');
        }
        $unRole = $this->_getRolesService()->findRoleById($id);
        if (empty($unRole)){
            $this->flashMessenger()->addWarningMessage($this->_getTranslatorHelper()->translate('The selected role was not find or does not exist anymore.'));
            return $this->redirect()->toRoute('zfcadmin/role');
        }
        return $unRole;
    }
    
    /**
     * get translatorHelper
     *
     * @return  Zend\Mvc\I18n\Translator
     */
    protected function _getTranslatorHelper()
    {
        if (null === $this->translatorHelper) {
            $this->translatorHelper = $this->getServiceLocator()->get('MvcTranslator');
        }
        return $this->translatorHelper;
    }
    
    /**
     * get rolesService
     *
     * @return Administration\Service\Roles
     */
    protected function _getRolesService()
    {
        if (null === $this->rolesService) {
            $this->rolesService = $this->getServiceLocator()->get('RolesService');
        }
        return $this->rolesService;
    }
    /**
     *
     * @return \Administration\Form\RoleForm
     */
    protected function _getForm($exceptId){
        if (null === $this->roleForm) {
            $this->roleForm = $this->getServiceLocator()->get('RoleForm');
            $roleFilter = $this->roleForm->getInputFilter();
            if ($roleFilter instanceof \Administration\Form\InputFilter\Role){
                $roleFilter->setExceptId($exceptId);
            }
        }
        return $this->roleForm;
    }
    /**
     * 
     * @return integer
     */
    protected function _getItemsPerPageByDefault(){
        if (null === $this->itemsPerPageDefault){
            $this->itemsPerPageDefault = $this->serviceLocator->get('items_per_page_default')->getItemsPerPageDefault();
        }
        return $this->itemsPerPageDefault;
    }
}