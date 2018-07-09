<?php
namespace mysql;

use Germania\Permissions\PdoUnassignRoleFromPermission;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;


class PdoUnassignRoleFromPermissionTest extends DatabaseTestCaseAbstract
{

    public $logger;


    public function setUp()
    {
        parent::setUp();
        $this->logger = new NullLogger;
    }



    public function testSimpleAdding(  )
    {

        $pdo = $this->getPdo();

        $sut = new PdoUnassignRoleFromPermission( $pdo, "permissions_roles", $this->logger);

        $permission_id = 2;
        $role_id = 1;

        $result = $sut($permission_id, $role_id);

        $this->assertInternalType("bool", $result);
        $this->assertTrue( $result);
    }


}

