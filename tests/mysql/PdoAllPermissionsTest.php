<?php
namespace mysql;

use Germania\Permissions\PdoAllPermissions;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;


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

    }



}
