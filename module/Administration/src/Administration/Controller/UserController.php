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

/**
 * 
 * @author alexandre
 *
 */
class UserController extends AbstractActionController
{

    public function indexAction()
    {
        return new ViewModel();
    }


}

