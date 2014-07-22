<?php

namespace Application\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class IndexControllerTest extends AbstractHttpControllerTestCase {
	protected $traceError = true;
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
	public function testFonctionActionCanBeAccessed() {

		$this->dispatch ( '/main/fonction' );
		$this->assertResponseStatusCode ( 200 );

		$this->assertModuleName ( 'Application' );
		$this->assertControllerName ( 'Application\Controller\Index' );
		$this->assertControllerClass ( 'IndexController' );
		$this->assertActionName('fonction');
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
}