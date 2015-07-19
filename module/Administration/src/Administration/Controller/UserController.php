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
use Application\Entity\User;
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
		    $message = "L’utilisateur %s était déjà actif. Aucune action n’a été entreprise !";
			$message = sprintf($this->_getTranslatorHelper()->translate($message),$user->getEmail());
			$this->flashMessenger()->addWarningMessage($message);
		    return $this->redirect()->toRoute('zfcadmin/user',['action'=>'view','user'=>$user->getId()]);
		}
		$confirmation = $this->params()->fromRoute('confirmation', self::NO_RESPONSE);
		if($confirmation == self::CONFIRM){
			$this->_getUsersService()->activeUser($user);
			$message = "L’utilisateur %s a été activé avec succès.";
			$message = sprintf($this->_getTranslatorHelper()->translate($message),$user->getEmail());
			$this->flashMessenger()->addSuccessMessage($message);
			return $this->redirect()->toRoute('zfcadmin/user',['action'=>'view','user'=>$user->getId()]);
		}elseif($confirmation == self::CANCEL){
			$message = sprintf($this->_getTranslatorHelper()->translate("Annulation demandée ! L’utilisateur %s n’a pas été activé."),$user->getEmail());
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
        $form  = $this->_getForm();
        $form->bind($user);
        $form->get('submit')->setAttribute('value', 'Edit');
        
        //Traitement de la requête
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
        
            if ($form->isValid()) {
                //Il faudrait passer par le service utilisateur
                $this->_getUsersService()->editUser($user);
                $message = "L’utilisateur %s a été modifié avec succès.";
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
			$message = "L’utilisateur %s a été supprimé avec succès.";
			$message = sprintf($this->_getTranslatorHelper()->translate($message),$user->getEmail());
			$this->flashMessenger()->addSuccessMessage($message);
			return $this->redirect()->toRoute('zfcadmin/user',['action'=>'list']);
		}elseif($confirmation == self::CANCEL){
			$message = sprintf($this->_getTranslatorHelper()->translate("Annulation demandée ! L’utilisateur %s n’a pas été supprimé."),$user->getEmail());
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
            $message = "L’utilisateur %s était déjà banni. Aucune action n’a été entreprise !";
            $message = sprintf($this->_getTranslatorHelper()->translate($message),$user->getEmail());
            $this->flashMessenger()->addWarningMessage($message);
            return $this->redirect()->toRoute('zfcadmin/user',['action'=>'view','user'=>$user->getId()]);
        }
        $confirmation = $this->params()->fromRoute('confirmation', self::NO_RESPONSE);
        if($confirmation == self::CONFIRM){
            $this->_getUsersService()->banUser($user);
            $message = "L’utilisateur %s a été banni avec succès.";
            $message = sprintf($this->_getTranslatorHelper()->translate($message),$user->getEmail());
            $this->flashMessenger()->addSuccessMessage($message);
            return $this->redirect()->toRoute('zfcadmin/user',['action'=>'view','user'=>$user->getId()]);
        }elseif($confirmation == self::CANCEL){
            $message = sprintf($this->_getTranslatorHelper()->translate("Annulation demandée ! L’utilisateur %s n’a pas été banni."),$user->getEmail());
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
            $message = "L’utilisateur %s n'était pas banni. Aucune action n’a été entreprise !";
            $message = sprintf($this->_getTranslatorHelper()->translate($message),$user->getEmail());
            $this->flashMessenger()->addWarningMessage($message);
            return $this->redirect()->toRoute('zfcadmin/user',['action'=>'view','user'=>$user->getId()]);
        }
        $confirmation = $this->params()->fromRoute('confirmation', self::NO_RESPONSE);
        if($confirmation == self::CONFIRM){
            $this->_getUsersService()->unbanUser($user);
            $message = "Le bannissement de l’utilisateur %s a été levé avec succès.";
            $message = sprintf($this->_getTranslatorHelper()->translate($message),$user->getEmail());
            $this->flashMessenger()->addSuccessMessage($message);
            return $this->redirect()->toRoute('zfcadmin/user',['action'=>'view','user'=>$user->getId()]);
        }elseif($confirmation == self::CANCEL){
            $message = sprintf($this->_getTranslatorHelper()->translate("Annulation demandée ! L’utilisateur %s est toujours banni."),$user->getEmail());
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
            $this->flashMessenger()->addErrorMessage($this->_getTranslatorHelper()->translate('Identifiant de l\'utilisateur invalide'));
            return $this->redirect()->toRoute('zfcadmin/user');
        }        
        $unUser = $this->_getUsersService()->findUserById($id);
        if (empty($unUser)){
            $this->flashMessenger()->addWarningMessage($this->_getTranslatorHelper()->translate('L\'utilisateur sélectionnée n\'a pas été trouvé ou n\'existe plus'));
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
    protected function _getForm(){
        if (null === $this->userForm) {
            $this->userForm = $this->getServiceLocator()->get('UserForm');
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

