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
use Administration\Entity\User;
use Administration\Form\Search;
use Zend\Http\Response;

/**
 * 
 * @author alexandre
 *
 */
class UserController extends AbstractActionController
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
     * @var \Administration\Form\UserForm
     */
    protected $userForm;
    /**
     * 
     * @var Administration\Service\Users
     */
    protected $usersService;

    /**
     * 
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function _getRepository(){
        return $this->_getEntityManager()->getRepository(User::class);
    }
    /**
     * Consulter un utilisateur
     * 
     * @return \Zend\Http\Response|\Zend\View\Model\ViewModel
     */
    public function viewAction(){
        $user = $this->_getUser();
        if ($user instanceof Response){
            //Redirection
            return $user;
        }
        return new ViewModel(array(
            'user'    => $user,
            'editIsAllowed' => $this->isAllowed('user', 'edit'),
            'banIsAllowed' => $this->isAllowed('user', 'ban'),
            'unbanIsAllowed' => $this->isAllowed('user', 'unban'),
        ));
    }
    /**
     * Activer un utilisateur
     * 
     * @return \Zend\Http\Response|\Zend\View\Model\ViewModel
     */
    public function activeAction(){
        $user = $this->_getUser();
		if ($user instanceof Response){
			//Redirection
			return $user;
		}
		if ($user->isActive()){
		    $message = _("This user %s was already activated. No action done !");
			$message = sprintf($this->_getTranslatorHelper()->translate($message),$user->getEmail());
			$this->flashMessenger()->addWarningMessage($message);
		    return $this->redirect()->toRoute('zfcadmin/user',['action'=>'view','user'=>$user->getId()]);
		}
		$confirmation = $this->params()->fromRoute('confirmation', self::NO_RESPONSE);
		if($confirmation == self::CONFIRM){
			$this->_getUsersService()->activeUser($user);
			$message = _("This user %s was succesfully activated.");
			$message = sprintf($this->_getTranslatorHelper()->translate($message),$user->getEmail());
			$this->flashMessenger()->addSuccessMessage($message);
			return $this->redirect()->toRoute('zfcadmin/user',['action'=>'view','user'=>$user->getId()]);
		}elseif($confirmation == self::CANCEL){
			$message = sprintf($this->_getTranslatorHelper()->translate("Cancellation ! This user %s has not been activated."),$user->getEmail());
			$this->flashMessenger()->addInfoMessage($message);
			return $this->redirect()->toRoute('zfcadmin/user',['action'=>'view','user'=>$user->getId()]);
		}
		return new ViewModel(array(
			'user'    => $user,
		    'confirm' => self::CONFIRM,
		    'cancel'  => self::CANCEL,
		));
    }
    /**
     * Editer un utilisateur
     *
     * @return \Zend\Http\Response|\Zend\View\Model\ViewModel
     */
    public function editAction(){
        $user = $this->_getUser();
        if ($user instanceof Response){
            //Redirection
            return $user;
        }
        //Création du formulaire
        $form  = $this->_getForm($user->getId());
        $form->bind($user);
        $form->get('submit')->setAttribute('value', 'Edit');
        
        //Traitement de la requête
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                //Il faudrait passer par le service utilisateur
                $this->_getUsersService()->editUser($user);
                $message = _("This user %s was successfully updated.");
                $message = sprintf($this->_getTranslatorHelper()->translate($message),$user->getEmail());
                $this->flashMessenger()->addSuccessMessage($message);
                // Redirect to edited user in view mode
                return $this->redirect()->toRoute('zfcadmin/user',['action'=>'view','user'=>$user->getId()]);
            }
        }
        
        return new ViewModel(array(
            'id' => $user->getId(),
            'form' => $form,
        ));
    }
    /**
     * Supprimer un utilisateur
     * 
     * @return \Zend\Http\Response|\Zend\View\Model\ViewModel
     */
    public function deleteAction(){
        $user = $this->_getUser();
		if ($user instanceof Response){
			//Redirection
			return $user;
		}
		$confirmation = $this->params()->fromRoute('confirmation', self::NO_RESPONSE);
		if($confirmation == self::CONFIRM){
			$this->_getUsersService()->deleteUser($user);
			$message = _("The user %s was successfully deleted.");
			$message = sprintf($this->_getTranslatorHelper()->translate($message),$user->getEmail());
			$this->flashMessenger()->addSuccessMessage($message);
			return $this->redirect()->toRoute('zfcadmin/user',['action'=>'list']);
		}elseif($confirmation == self::CANCEL){
			$message = sprintf($this->_getTranslatorHelper()->translate("Cancellation ! This user %s was not deleted."),$user->getEmail());
			$this->flashMessenger()->addInfoMessage($message);
			return $this->redirect()->toRoute('zfcadmin/user',['action'=>'view','user'=>$user->getId()]);
		}
		return new ViewModel(array(
			'user'    => $user,
		    'confirm' => self::CONFIRM,
		    'cancel'  => self::CANCEL,
		));
    }
    /**
     * Bannir un utilisateur
     *
     * @return \Zend\Http\Response|\Zend\View\Model\ViewModel
     */
    public function banAction(){
        $user = $this->_getUser();
        if ($user instanceof Response){
            //Redirection
            return $user;
        }
        if ($user->isBanned()){
            $message = _("This user %s was already banned. No action done !");
            $message = sprintf($this->_getTranslatorHelper()->translate($message),$user->getEmail());
            $this->flashMessenger()->addWarningMessage($message);
            return $this->redirect()->toRoute('zfcadmin/user',['action'=>'view','user'=>$user->getId()]);
        }
        $confirmation = $this->params()->fromRoute('confirmation', self::NO_RESPONSE);
        if($confirmation == self::CONFIRM){
            $this->_getUsersService()->banUser($user);
            $message = _("This user %s was successfully banned.");
            $message = sprintf($this->_getTranslatorHelper()->translate($message),$user->getEmail());
            $this->flashMessenger()->addSuccessMessage($message);
            return $this->redirect()->toRoute('zfcadmin/user',['action'=>'view','user'=>$user->getId()]);
        }elseif($confirmation == self::CANCEL){
            $message = sprintf($this->_getTranslatorHelper()->translate("Cancellation ! This user %s was not banned."),$user->getEmail());
            $this->flashMessenger()->addInfoMessage($message);
            return $this->redirect()->toRoute('zfcadmin/user',['action'=>'view','user'=>$user->getId()]);
        }
        return new ViewModel(array(
            'user'    => $user,
            'confirm' => self::CONFIRM,
            'cancel'  => self::CANCEL,
        ));
    }
    /**
     * «Débannir» un utilisateur
     *
     * @return \Zend\Http\Response|\Zend\View\Model\ViewModel
     */
    public function unbanAction(){
        $user = $this->_getUser();
        if ($user instanceof Response){
            //Redirection
            return $user;
        }
        if (!$user->isBanned()){
            $message = _("This user %s was not banned. No action done !");
            $message = sprintf($this->_getTranslatorHelper()->translate($message),$user->getEmail());
            $this->flashMessenger()->addWarningMessage($message);
            return $this->redirect()->toRoute('zfcadmin/user',['action'=>'view','user'=>$user->getId()]);
        }
        $confirmation = $this->params()->fromRoute('confirmation', self::NO_RESPONSE);
        if($confirmation == self::CONFIRM){
            $this->_getUsersService()->unbanUser($user);
            $message = _("This user %s is no more banned.");
            $message = sprintf($this->_getTranslatorHelper()->translate($message),$user->getEmail());
            $this->flashMessenger()->addSuccessMessage($message);
            return $this->redirect()->toRoute('zfcadmin/user',['action'=>'view','user'=>$user->getId()]);
        }elseif($confirmation == self::CANCEL){
            $message = sprintf($this->_getTranslatorHelper()->translate("Cancellation ! This user remains banned."),$user->getEmail());
            $this->flashMessenger()->addInfoMessage($message);
            return $this->redirect()->toRoute('zfcadmin/user',['action'=>'view','user'=>$user->getId()]);
        }
        return new ViewModel(array(
            'user'    => $user,
            'confirm' => self::CONFIRM,
            'cancel'  => self::CANCEL,
        ));
    }
    /**
     * Lister les utilisateurs
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
        $zend_paginator = $this->_getUsersService()->searchUsers($searchText,$sort);
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
            'viewIsAllowed'   => $this->isAllowed('user', 'view'),
            'editIsAllowed'   => $this->isAllowed('user', 'edit'),
            'deleteIsAllowed' => $this->isAllowed('user', 'delete'),
            'banIsAllowed'    => $this->isAllowed('user', 'ban'),
            'unbanIsAllowed'  => $this->isAllowed('user', 'unban'),
            'activatingIsAllowed' => $this->isAllowed('user', 'activating'),
        ));
    }
    
    /**
     * 
     * @return User|Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>
     */
    protected function _getUser(){
        $id = (int) $this->params()->fromRoute('user', 0);
        if ($id == 0) {
            $this->flashMessenger()->addErrorMessage($this->_getTranslatorHelper()->translate('Invalid user Id'));
            return $this->redirect()->toRoute('zfcadmin/user');
        }        
        $unUser = $this->_getUsersService()->findUserById($id);
        if (empty($unUser)){
            $this->flashMessenger()->addWarningMessage($this->_getTranslatorHelper()->translate('The selected user was not find or does not exist anymore.'));
            return $this->redirect()->toRoute('zfcadmin/user');
        }
        return $unUser;
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
     * get usersService
     *
     * @return Administration\Service\Users
     */
    protected function _getUsersService()
    {
        if (null === $this->usersService) {
            $this->usersService = $this->getServiceLocator()->get('UsersService');
        }
        return $this->usersService;
    }
    /**
     * 
     * @return \Administration\Form\UserForm
     */
    protected function _getForm($exceptId){
        if (null === $this->userForm) {
            $this->userForm = $this->getServiceLocator()->get('UserForm');
            $userFilter = $this->userForm->getInputFilter();
            if ($userFilter instanceof \Administration\Form\InputFilter\User){
                $userFilter->setExceptId($exceptId);
            }
        }
        return $this->userForm;
    }
    /**
     * @return integer
     */
    protected function _getItemsPerPageByDefault(){
        if (null === $this->itemsPerPageDefault){
            $this->itemsPerPageDefault = $this->serviceLocator->get('items_per_page_default')->getItemsPerPageDefault();
        }
        return $this->itemsPerPageDefault;
    }
}

