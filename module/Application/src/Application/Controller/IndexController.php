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
	/**
	 * 
	 * @var array
	 */
	protected $options;
	
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
    public function sitemapAction()
    {
    	$viewmodel = new ViewModel();
    	$viewmodel->setTerminal(true);
    	return $viewmodel;
    }
    public function suivreAction()
    {
    	//Récupération de la configuration Mantis
    	$mantis = $this->getOptions();
    	if (is_array($mantis) && key_exists('username',$mantis) && key_exists('password',$mantis) && key_exists('projectId',$mantis) && key_exists('evolutionFilterId',$mantis) && key_exists('bugFilterId',$mantis)){
    		$username = $mantis['username'];
    		$password = $mantis['password'];
    		$projectId = $mantis['projectId'];
    		$filtreDemandeEvolution = $mantis['evolutionFilterId'];
    		$filtreBugBloquant = $mantis['bugFilterId'];
    		
    		//Appel du Service SOAP
    		try{
    			$c = new \SoapClient($mantis['soap']);
    			$versions = $c->mc_project_get_versions($username,$password,$projectId);
    			$demandes = $c->mc_filter_get_issues($username,$password,$projectId,$filtreDemandeEvolution);
    			$bugs = $c->mc_filter_get_issues($username,$password,$projectId,$filtreBugBloquant);
    			unset($c);
    		}catch (\SoapFault $soapFault){
    			//@todo log error
    			return new ViewModel(
	    			array(
	    				'error'	=> true,
	       			)
        		);
    		}
    	} else {
    		//@todo log configuration file not load and not parametred
	    	return new ViewModel(
	    		array(
	    			'error'	=> true,
	       		)
        	);
    	}
   		//Création de la vue
        return new ViewModel(
    		array(
    			'error'	=> false,
    			'versions' => $versions,
    			'bugs' => $bugs,
    			'demandes' => $demandes
       		)
        );
    }
    /**
     * set options
     *
     * @return IndexController
     */
    public function setOptions($options)
    {
    	$this->options = $options;
    
    	return $this;
    }
    
    /**
     * get options
     *
     * @return ModuleOptions
     */
    public function getOptions()
    {
    	if (!$this->options) {
    		$this->setOptions($this->getServiceLocator()->get('mantis'));
    	}
    
    	return $this->options;
    }    
}
