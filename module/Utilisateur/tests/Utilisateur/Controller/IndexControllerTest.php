<?php

namespace Utilisateur\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class UtilisateurControllerTest extends AbstractHttpControllerTestCase {
	protected $traceError = true;

	public function setUp() {
		$this->setApplicationConfig ( include __DIR__ . '/../../../../../config/application.config.php' );
		parent::setUp ();
	}
	public function testIndexActionCanBeAccessed() {
		$this->dispatch ( '/utilisateur' );
		$this->assertResponseStatusCode ( 200 );
		
		$this->assertModuleName ( 'Utilisateur' );
		$this->assertControllerName ( 'Utilisateur\Controller\Index' );
		$this->assertControllerClass ( 'IndexController' );
		$this->assertMatchedRouteName ( 'utilisateur' );
	}
	public function testLoginActionCanBeAccessed() {
		$this->dispatch ( '/login' );
		$this->assertResponseStatusCode ( 200 );
	
		$this->assertModuleName ( 'Utilisateur' );
		$this->assertControllerName ( 'Utilisateur\Controller\Index' );
		$this->assertControllerClass ( 'IndexController' );
		$this->assertMatchedRouteName ( 'login' );
	}	
	/*
	public function testAddActionRedirectsAfterValidPost()
	{
		$albumTableMock = $this->getMockBuilder('Album\Model\AlbumTable')
		->disableOriginalConstructor()
		->getMock();
	
		$albumTableMock->expects($this->once())
		->method('saveAlbum')
		->will($this->returnValue(null));
	
		$serviceManager = $this->getApplicationServiceLocator();
		$serviceManager->setAllowOverride(true);
		$serviceManager->setService('Album\Model\AlbumTable', $albumTableMock);
	
		$postData = array(
				'title'  => 'Led Zeppelin III',
				'artist' => 'Led Zeppelin',
		);
		$this->dispatch('/album/add', 'POST', $postData);
		$this->assertResponseStatusCode(302);
	
		$this->assertRedirectTo('/album');
	}*/
}