<?php
namespace mysql;

use Germania\Permissions\PdoUpdatePermissionRoles;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;


class PdoUpdatePermissionRolesTest extends DatabaseTestCaseAbstract
{

    public $logger;


    public function setUp()
    {
        parent::setUp();
        $this->logger = new NullLogger;
    }



    public function testUpdating(  )
    {

        $pdo = $this->getPdo();

        $sut = new PdoUpdatePermissionRoles( $pdo, "permissions", "permissions_roles", $this->logger);

        $result = $sut('foo', [ 2,3,4]);

        $this->assertTrue( $result );

    }



}
