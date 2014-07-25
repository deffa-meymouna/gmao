<?php

namespace Reseau\Controller;

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

		$this->dispatch ( '/reseau' );
		//@todo Test with member role as default for the 200 response
		$this->assertResponseStatusCode ( 403 );
		$this->assertModuleName ( 'Reseau' );
		$this->assertControllerName ( 'Reseau\Controller\Index' );
		$this->assertControllerClass ( 'IndexController' );
		$this->assertActionName('index');
		$this->assertMatchedRouteName ( 'reseau' );

		$this->dispatch ( '/reseau/index' );
		//@todo Test with member role as default for the 200 response
		$this->assertResponseStatusCode ( 403 );
		$this->assertModuleName ( 'Reseau' );
		$this->assertControllerName ( 'Reseau\Controller\Index' );
		$this->assertControllerClass ( 'IndexController' );
		$this->assertActionName('index');
		$this->assertMatchedRouteName ( 'reseau' );
	}
}