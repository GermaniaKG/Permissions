<?php
namespace mysql;

use Germania\Permissions\PdoAddPermission;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;


class PdoAddPermissionTest extends DatabaseTestCaseAbstract
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

        $sut = new PdoAddPermission( $pdo, "permissions", $this->logger);
        $result = $sut([
            'name' => 'test',
            'description' => 'This is a description'
        ]);
        $this->assertInternalType("bool", $result);
    }



}
