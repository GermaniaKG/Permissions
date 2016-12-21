<?php
namespace tests;

use Germania\Permissions\PermissionsAcl;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Prophecy\Argument;


class PermissionsAclTest extends \PHPUnit_Framework_TestCase
{

    public $logger;


    public function setUp()
    {
        parent::setUp();
        $this->logger = new NullLogger;
    }



    public function testInstantiation(  )
    {

        $stmt_mock = $this->prophesize(\PDOStatement::class);
        $stmt_mock->execute()->willReturn( true );
        $stmt_mock->fetchAll( Argument::any() )->willReturn( array() );
        $stmt = $stmt_mock->reveal();

        $pdo_mock = $this->prophesize(\PDO::class);
        $pdo_mock->prepare( Argument::type('string') )->willReturn( $stmt );

        $pdo = $pdo_mock->reveal();

        $sut = new PermissionsAcl( $pdo, null, null, $this->logger);
        $acl = $sut();
        $this->assertInternalType("array", $acl);
    }



}
