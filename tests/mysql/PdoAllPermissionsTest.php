<?php
namespace mysql;

use Germania\Permissions\PdoAllPermissions;
use Germania\Permissions\PermissionNotFoundException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;


class PdoAllPermissionsTest extends DatabaseTestCaseAbstract
{

    public $logger;


    public function setUp()
    {
        parent::setUp();
        $this->logger = new NullLogger;
    }



    public function testInstantiation(  )
    {

        $pdo = $this->getPdo();

        $sut = new PdoAllPermissions( $pdo, "permissions", "permissions_roles", $this->logger);

        $this->assertInstanceOf( \Countable::class, ($sut) );
        $this->assertInstanceOf( \Traversable::class, $sut );
        $this->assertInstanceOf( ContainerInterface::class, $sut );

    }


    public function testContainerInterface(  )
    {

        $pdo = $this->getPdo();

        $sut = new PdoAllPermissions( $pdo, "permissions", "permissions_roles", $this->logger);

        $this->assertInternalType("bool", $sut->has("foo"));
        $this->assertNotEmpty($sut->get("foo"));

        $this->assertFalse($sut->has("does-not-exist"));
        $this->expectException( PermissionNotFoundException::class );
        $this->expectException( NotFoundExceptionInterface::class );
        $sut->get("does-not-exist");

    }



}
