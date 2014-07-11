<?php

namespace Utilisateur\Authentication\Adapter;

use Zend\Authentication\Adapter;
use Utilisateur\Authentication\CasResult as Result;
use Zend\Config\Config;
use phpCAS;

class Cas implements Adapter\AdapterInterface {
	
	/**
	 * Host Server Cas
	 * 
	 * @var string
	 */
	private $_host;
	
	/**
	 * Port Server Cas
	 *
	 * @var integer
	 */
	private $_port;
	
	/**
	 * Context Server Cas
	 *
	 * @var string
	 */
	private $_context;
	
	/**
	 * Is Certificat needed for Server Cas
	 *
	 * @var boolean
	 */
	private $_certificat = false;
	
	/**
	 * Path of Certificat
	 *
	 * @var string
	 */
	private $_pathCertificat;
	
	/**
	 * Application Exit URL for Cas Server
	 * 
	 * @var string
	 */
	private $_applicationExitURL;
	
	/**
	 * (non-PHPdoc)
	 * 
	 * @see \Zend\Authentication\Adapter\AdapterInterface::authenticate()
	 * @return Utilisateur\Authentication\CasResult
	 */
	public function authenticate() {
		
		// Initialisation du client CAS.
		phpCAS::client ( CAS_VERSION_2_0, $this->getHost(), $this->getPort(), $this->getContext(), true );
		
		// Le certificat de l'autorité IGC/A.
		if ($this->needCertificat()){
			phpCAS::setCasServerCACert($this->getPathCertificat());
		}else{
			phpCAS::setNoCasServerValidation ();
		}
		
		// L'authentification retourne vrai ou stoppe le process
		phpCAS::forceAuthentication ();
		//On a une adresse mail uniquement s'il est connecté. Sinon ça plante de toute façon
		return new Result (Result::SUCCESS,phpCAS::getUser ());
	}
	/**
	 * (non-PHPdoc)
	 *
	 * @see \Zend\Authentication\Adapter\AdapterInterface::authenticate()
	 * @return Utilisateur\Authentication\CasResult
	 */
	public function logout() {
	
		// Initialisation du client CAS.
		phpCAS::client ( CAS_VERSION_2_0, $this->getHost(), $this->getPort(), $this->getContext(), true );
	
		// Le certificat de l'autorité IGC/A.
		if ($this->needCertificat()){
			phpCAS::setCasServerCACert($this->getPathCertificat());
		}else{
			phpCAS::setNoCasServerValidation ();
		}
		
		phpCAS::logoutWithRedirectService ( $this->getApplicationExitURL() );
	
		return true;
	}
	/**
	 * Set adapater state from options array
	 *
	 * @param array $options        	
	 * @return Zend_Auth_Adapter_Cas
	 */
	public function setOptions(array $options) {
		$forbidden = array (
				'Options',
				'Config' 
		);
		
		foreach ( $options as $key => $value ) {
			$normalized = ucfirst ( $key );
			if (in_array ( $normalized, $forbidden )) {
				continue;
			}
			
			$method = 'set' . $normalized;
			
			if (method_exists ( $this, $method )) {
				$this->$method ( $value );
			}else{
				die($method);
			}
		}
		
		// Set the URL
		/*$url = $this->getUrl ();
		
		if (empty ( $url )) {
			$this->setUrl ();
		}
		
		// Set the service URL
		$service = $this->getService ();
		
		if (empty ( $service )) {
			$this->setService ();
		}
		
		// Set the login URL
		$loginUrl = $this->getLoginUrl ();
		
		if (empty ( $loginUrl )) {
			$this->setLoginUrl ();
		}
		
		// Set the logout URL
		$logoutUrl = $this->getLogoutUrl ();
		
		if (empty ( $logoutUrl )) {
			$this->setLogoutUrl ();
		}
		*/
		return $this;
	}
	/**
	 * Set adapter from config object
	 *
	 * @param  Zend_Config $config
	 * @return Zend_Auth_Adapter_Cas
	 */
	public function setConfig(Config $config)
	{
		return $this->setOptions($config->toArray());
	}
	/**
	 * @return the $_host
	 */
	public function getHost() {
		return $this->_host;
	}

	/**
	 * @param string $_host
	 */
	public function setHost($_host) {
		$this->_host = $_host;
	}

	/**
	 * @return the $_port
	 */
	public function getPort() {
		return $this->_port;
	}

	/**
	 * @param number $_port
	 */
	public function setPort($_port) {
		$this->_port = $_port;
	}

	/**
	 * @return the $_context
	 */
	public function getContext() {
		return $this->_context;
	}

	/**
	 * @param string $_context
	 */
	public function setContext($_context) {
		$this->_context = $_context;
	}

	/**
	 * @return the $_certificat
	 */
	public function getCertificat() {
		return $this->_certificat;
	}
	
	/**
	 * @return the $_certificat
	 */
	public function needCertificat() {
		return $this->_certificat;
	}

	/**
	 * @param boolean $_certificat
	 */
	public function setCertificat($_certificat) {
		$this->_certificat = $_certificat;
	}

	/**
	 * @return the $_pathCertificat
	 */
	public function getPathCertificat() {
		return $this->_pathCertificat;
	}

	/**
	 * @param string $_pathCertificat
	 */
	public function setPathCertificat($_pathCertificat) {
		$this->_pathCertificat = $_pathCertificat;
	}

	/**
	 * @return the $_applicationExitURL
	 */
	public function getApplicationExitURL() {
		return $this->_applicationExitURL;
	}

	/**
	 * @param string $_applicationExitURL
	 */
	public function setApplicationExitURL($_applicationExitURL) {
		$this->_applicationExitURL = $_applicationExitURL;
	}

}

