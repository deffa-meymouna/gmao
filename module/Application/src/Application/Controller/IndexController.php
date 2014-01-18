<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    public function contactAction()
    {
        return new ViewModel();
    }
    public function serviceAction()
    {
        return new ViewModel();
    }
    public function fonctionAction()
    {
        return new ViewModel();
    }
    public function suivreAction()
    {
    	//FIXME : Find the good practice to Invoke config;
    	$config = $this->getEvent()->getApplication()->getConfig();
    	$username = $config['mantis']['username'];
    	$password = $config['mantis']['password'];
    	$projectId = $config['mantis']['projectId'];
    	$filtreDemandeEvolution = $config['mantis']['evolutionFilterId'];
    	$filtreBugBloquant = $config['mantis']['bugFilterId'];
    	
    	//Appel du Service SOAP
   		$c = new \SoapClient($config['mantis']['soap']);
    	$versions = $c->mc_project_get_versions($username,$password,$projectId);
   		$demandes = $c->mc_filter_get_issues($username,$password,$projectId,$filtreDemandeEvolution);
   		$bugs = $c->mc_filter_get_issues($username,$password,$projectId,$filtreBugBloquant);
    	unset($c);
    	
   		//Création de la vue
        return new ViewModel(
    		array(
    			'versions' => $versions,
    			'bugs' => $bugs,
    			'demandes' => $demandes
       		)
        );
    }
}
