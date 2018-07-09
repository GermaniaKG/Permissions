<?php
namespace mysql;

use Germania\Permissions\PdoUpdatePermissionRoles;
use Germania\Permissions\PermissionNotFoundException;
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

    public function testNotFoundException()
    {
        $pdo = $this->getPdo();

        $sut = new PdoUpdatePermissionRoles( $pdo, "permissions", "permissions_roles", $this->logger);

        $this->expectException( PermissionNotFoundException::class );
        $result = $sut('does_not_exist', [ 2,3,4]);

    }

}
