<?php
namespace mysql;

use Germania\Permissions\PermissionsAcl;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;


class PermissionsAclTest extends DatabaseTestCaseAbstract
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

        $sut = new PermissionsAcl( $pdo, "permissions", "permissions_roles", $this->logger);
        $acl = $sut();
        $this->assertInternalType("array", $acl);
    }



}
