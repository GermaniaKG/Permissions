<?php
namespace mysql;

use Germania\Permissions\PdoAssignRoleToPermission;
use Germania\Permissions\RolePermissionAssignmentExistsException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;


class PdoAssignRoleToPermissionTest extends DatabaseTestCaseAbstract
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

        $sut = new PdoAssignRoleToPermission( $pdo, "permissions_roles", $this->logger);
        $permission_id = 1;
        $role_id = 100;

        $result = $sut($permission_id, $role_id);

        $this->assertTrue(is_numeric($result));
        $this->assertInternalType("integer", $result);
    }


    public function testDuplicateName(  )
    {

        $pdo = $this->getPdo();

        $sut = new PdoAssignRoleToPermission( $pdo, "permissions_roles", $this->logger);

        $permission_id = 1;
        $role_id = 100;

        $this_should_work = $sut($permission_id, $role_id);

        $this->expectException( RolePermissionAssignmentExistsException::class );

        $try_duplicate = $sut($permission_id, $role_id);
    }



}
