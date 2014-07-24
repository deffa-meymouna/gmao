<?php

namespace Application\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

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
	public function testIndexActionCanBeAccessed() {

		$this->dispatch ( '' );
		$this->assertResponseStatusCode ( 200 );
		$this->assertModuleName ( 'Application' );
		$this->assertControllerName ( 'Application\Controller\Index' );
		$this->assertControllerClass ( 'IndexController' );
		$this->assertActionName('index');
		$this->assertMatchedRouteName ( 'home' );

		$this->dispatch ( '/' );
		$this->assertResponseStatusCode ( 200 );
		$this->assertModuleName ( 'Application' );
		$this->assertControllerName ( 'Application\Controller\Index' );
		$this->assertControllerClass ( 'IndexController' );
		$this->assertActionName('index');
		$this->assertMatchedRouteName ( 'home' );

		$this->dispatch ( '/main' );
		$this->assertResponseStatusCode ( 200 );
		$this->assertModuleName ( 'Application' );
		$this->assertControllerName ( 'Application\Controller\Index' );
		$this->assertControllerClass ( 'IndexController' );
		$this->assertActionName('index');
		$this->assertMatchedRouteName ( 'application' );

		$this->dispatch ( '/main/index' );
		$this->assertResponseStatusCode ( 200 );
		$this->assertModuleName ( 'Application' );
		$this->assertControllerName ( 'Application\Controller\Index' );
		$this->assertControllerClass ( 'IndexController' );
		$this->assertActionName('index');
		$this->assertMatchedRouteName ( 'application' );
	}
	public function testFonctionActionCanBeAccessed() {

		$this->dispatch ( '/main/fonction' );
		$this->assertResponseStatusCode ( 200 );

		$this->assertModuleName ( 'Application' );
		$this->assertControllerName ( 'Application\Controller\Index' );
		$this->assertControllerClass ( 'IndexController' );
		$this->assertActionName('fonction');
		$this->assertMatchedRouteName ( 'application' );
	}
	public function testServiceActionCanBeAccessed() {

		$this->dispatch ( '/main/service' );
		$this->assertResponseStatusCode ( 200 );

		$this->assertModuleName ( 'Application' );
		$this->assertControllerName ( 'Application\Controller\Index' );
		$this->assertControllerClass ( 'IndexController' );
		$this->assertActionName('service');
		$this->assertMatchedRouteName ( 'application' );
	}
	public function testContactActionCanBeAccessed() {

		$this->dispatch ( '/main/contact' );
		$this->assertResponseStatusCode ( 200 );

		$this->assertModuleName ( 'Application' );
		$this->assertControllerName ( 'Application\Controller\Index' );
		$this->assertControllerClass ( 'IndexController' );
		$this->assertActionName('contact');
		$this->assertMatchedRouteName ( 'application' );
	}
	public function testSuivreActionCanBeAccessed() {

		$this->dispatch ( '/main/suivre' );
		$this->assertResponseStatusCode ( 200 );

		$this->assertModuleName ( 'Application' );
		$this->assertControllerName ( 'Application\Controller\Index' );
		$this->assertControllerClass ( 'IndexController' );
		$this->assertActionName('suivre');
		$this->assertMatchedRouteName ( 'application' );
	}
	public function testExemple1ActionCanBeAccessed() {

		$this->dispatch ( '/main/exemple1' );
		$this->assertResponseStatusCode ( 200 );

		$this->assertModuleName ( 'Application' );
		$this->assertControllerName ( 'Application\Controller\Index' );
		$this->assertControllerClass ( 'IndexController' );
		$this->assertActionName('exemple1');
		$this->assertMatchedRouteName ( 'application' );
	}
	public function testExemple2ActionCanBeAccessed() {

		$this->dispatch ( '/main/exemple2' );
		$this->assertResponseStatusCode ( 200 );

		$this->assertModuleName ( 'Application' );
		$this->assertControllerName ( 'Application\Controller\Index' );
		$this->assertControllerClass ( 'IndexController' );
		$this->assertActionName('exemple2');
		$this->assertMatchedRouteName ( 'application' );
	}
	public function testSiteMapActionCanBeAccessed() {

		$this->dispatch ( '/main/sitemap' );
		$this->assertResponseStatusCode ( 200 );

		$this->assertModuleName ( 'Application' );
		$this->assertControllerName ( 'Application\Controller\Index' );
		$this->assertControllerClass ( 'IndexController' );
		$this->assertActionName('sitemap');
		$this->assertMatchedRouteName ( 'application' );
	}
	public function testLockActionCanNotBeAccessed() {

		$this->dispatch ( '/main/lock' );
		$this->assertResponseStatusCode ( 403 );

		$this->assertModuleName ( 'Application' );
		$this->assertControllerName ( 'Application\Controller\Index' );
		$this->assertControllerClass ( 'IndexController' );
		$this->assertActionName('lock');
		$this->assertMatchedRouteName ( 'application' );
	}
}