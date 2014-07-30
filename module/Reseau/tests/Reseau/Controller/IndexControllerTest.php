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