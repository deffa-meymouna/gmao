<?php

namespace Reseau\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use CsnUser\Entity\User;
use CsnUser\Entity\Role;

class IndexControllerTest extends AbstractHttpControllerTestCase {
	//Trace error activated
	protected $traceError = true;
	/**
	 * Set Up from
	 * @link http://framework.zend.com/manual/2.3/en/modules/zend.test.phpunit.html
	 */
	public function setUp() {
		$this->setApplicationConfig ( include __DIR__ . '/../../../../../config/application.config.php' );
		parent::setUp ();
	}

	public function testIndexActionCanNotBeAccessedByGuest() {
		//En tant qu'invité tout est bloqué
		$this->dispatch ( '/reseau' );
		$this->assertResponseStatusCode ( 403);

		$this->dispatch ( '/reseau/index' );
		$this->assertResponseStatusCode ( 403 );
	}

	public function testIndexActionCanBeAccessedByMember() {
		$this->mockLogin('member');
		$this->dispatch ( '/reseau' );
		$this->assertResponseStatusCode ( 200 );
		$this->assertModuleName ( 'Reseau' );
		$this->assertControllerName ( 'Reseau\Controller\Index' );
		$this->assertControllerClass ( 'IndexController' );
		$this->assertActionName('index');
		$this->assertMatchedRouteName ( 'reseau' );

		$this->dispatch ( '/reseau/index' );
		$this->assertResponseStatusCode ( 200 );
		$this->assertModuleName ( 'Reseau' );
		$this->assertControllerName ( 'Reseau\Controller\Index' );
		$this->assertControllerClass ( 'IndexController' );
		$this->assertActionName('index');
		$this->assertMatchedRouteName ( 'reseau' );
	}
	public function testSupprimerActionCanNotBeAccessedByMember() {
		$this->mockLogin('member');
		$this->dispatch ( '/reseau/supprimer' );
		$this->assertResponseStatusCode ( 403 );
		$this->assertModuleName ( 'Reseau' );
		$this->assertControllerName ( 'Reseau\Controller\Index' );
		$this->assertControllerClass ( 'IndexController' );
		$this->assertActionName('supprimer');
		$this->assertMatchedRouteName ( 'reseau' );
	}
	public function testSupprimerActionCanBeAccessedByTechnician() {
		$this->mockLogin('technician');
		//@todo MOCK ServiceReseau !
		$this->dispatch ( '/reseau/supprimer/2' );
		$this->assertResponseStatusCode ( 200 );
		$this->assertModuleName ( 'Reseau' );
		$this->assertControllerName ( 'Reseau\Controller\Index' );
		$this->assertControllerClass ( 'IndexController' );
		$this->assertActionName('supprimer');
		$this->assertMatchedRouteName ( 'reseau' );
	}
	public function testSupprimerActionWithoutReseauIsRedirected(){
		$this->mockLogin('technician');
		$this->dispatch ( '/reseau/supprimer' );
		$this->assertResponseStatusCode ( 302 );
		$this->assertModuleName ( 'Reseau' );
		$this->assertControllerName ( 'Reseau\Controller\Index' );
		$this->assertControllerClass ( 'IndexController' );
		$this->assertActionName('supprimer');
		$this->assertMatchedRouteName ( 'reseau' );
		$this->assertRedirectTo('/reseau');
	}
	public function testCreerActionWithoutReseauCanNotBeAccessedByMember(){
		$this->mockLogin('member');
		$this->dispatch ( '/reseau/creer' );
		$this->assertResponseStatusCode ( 403 );
		$this->assertModuleName ( 'Reseau' );
		$this->assertControllerName ( 'Reseau\Controller\Index' );
		$this->assertControllerClass ( 'IndexController' );
		$this->assertActionName('creer');
		$this->assertMatchedRouteName ( 'reseau' );
	}
	public function testCreerActionWithoutReseauCanBeAccessedByTechnician(){
		$this->mockLogin('technician');
		$this->dispatch ( '/reseau/creer' );
		$this->assertResponseStatusCode ( 200 );
		$this->assertModuleName ( 'Reseau' );
		$this->assertControllerName ( 'Reseau\Controller\Index' );
		$this->assertControllerClass ( 'IndexController' );
		$this->assertActionName('creer');
		$this->assertMatchedRouteName ( 'reseau' );
	}
	public function testCreerActionInValidFormIsNotSaved(){
		$post = array(
				'foo' => 'foobar',
				'bar' => 'foobar'
		);
		$this->mockLogin('technician');
		$this->dispatch ( '/reseau/creer', 'POST', $post );
		$this->assertResponseStatusCode ( 200 );
		$this->assertModuleName ( 'Reseau' );
		$this->assertControllerName ( 'Reseau\Controller\Index' );
		$this->assertControllerClass ( 'IndexController' );
		$this->assertActionName('creer');
		$this->assertMatchedRouteName ( 'reseau' );
	}




	/**
	 * Mock Role
	 * @param string role
	 */
	protected function mockLogin($role){
		$user = new User();
		$userRole = new Role();
		$userRole->setName($role);
		$user->setRole($userRole);
		$user->setEmail('test@cerema.fr');
		$authMock = $this->getMock('Zend\Authentication\AuthenticationService');
		$authMock->expects($this->any())
			->method('hasIdentity')
			-> will($this->returnValue(true));

		$authMock->expects($this->any())
			->method('getIdentity')
			->will($this->returnValue($user));

		$this->getApplicationServiceLocator()->setAllowOverride(true);
		$this->getApplicationServiceLocator()->setService('Zend\Authentication\AuthenticationService', $authMock);
	}
}