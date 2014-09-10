<?php
//require_once 'module/Reseau/src/Reseau/Entity/Table/Machine.php';

//require_once 'PHPUnit/Framework/TestCase.php';

use Reseau\Entity\Table\Machine;
use Reseau\Entity\Table\Reseau;
use Reseau\Entity\Table\Ip;

/**
 * Machine test case.
 */
class MachineTest extends PHPUnit_Framework_TestCase {

	/**
	 *
	 * @var Machine
	 */
	private $Machine;

	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		$this->Machine = new Machine();
	}

	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated MachineTest::tearDown()
		$this->Machine = null;

		parent::tearDown ();
	}

	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}

	/**
	 * Tests Machine->getReseauCount()
	 */
	public function testGetReseauCount() {

		$this->assertEquals(0, $this->Machine->getReseauCount());
		$reseau = new Reseau();
		$ip = new Ip();
		$ip->setReseau($reseau);
		$ip->setMachine($this->Machine);
		$this->assertEquals(1, $this->Machine->getReseauCount());
		$ip2 = new Ip();
		$ip2->setReseau($reseau);
		$ip2->setMachine($this->Machine);
		$this->assertEquals(1, $this->Machine->getReseauCount());
		// TODO Auto-generated MachineTest->testGetReseauCount()
		$this->markTestIncomplete ( "getReseauCount test not fully implemented" );
	}

	/**
	 * Tests Machine->getIpCount()
	 */
	public function testGetIpCount() {
		// TODO Auto-generated MachineTest->testGetIpCount()
		$this->markTestIncomplete ( "getIpCount test not implemented" );

		$this->Machine->getIpCount(/* parameters */);
	}

	/**
	 * Tests Machine->getUsername()
	 */
	public function testGetUsername() {
		// TODO Auto-generated MachineTest->testGetUsername()
		$this->markTestIncomplete ( "getUsername test not implemented" );

		$this->Machine->getUsername(/* parameters */);
	}
}

