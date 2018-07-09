<?php
namespace mysql;

use Germania\Permissions\PdoAddPermission;
use Germania\Permissions\PermissionNameExistsException;
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
            'name' => 'to-be-inserted',
            'description' => 'This is a description'
        ]);

        $this->assertTrue(is_numeric($result));
        $this->assertInternalType("integer", $result);
    }


    public function testDuplicateName(  )
    {

        $pdo = $this->getPdo();

        $sut = new PdoAddPermission( $pdo, "permissions", $this->logger);

        $data = [
            'name' => 'to-be-inserted',
            'description' => 'This is a description'
        ];
        $this_should_work = $sut($data);

        $this->expectException( PermissionNameExistsException::class );

        $try_duplicate = $sut($data);
    }



}
