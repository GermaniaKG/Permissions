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


        $sut = new PermissionsAcl( $pdo, null, null, $this->logger);
        $acl = $sut();
        $this->assertInternalType("array", $acl);
        print_r($acl);
    }



}
