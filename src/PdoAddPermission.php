<?php
namespace Germania\Permissions;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class PdoAddPermission
{

    use LoggerAwareTrait;

    /**
     * @var \PDOStatement
     */
    public $stmt;

    /**
     * @var string
     */
    public $permissions_table = "permissions";

    /**
     * @var Seperator string for roles in SELECT statement
     */
    protected $separator = ",";


    /**
     * @param \PDO                 $pdo                      PDO instance
     * @param string               $permissions_table        Permissions table name
     * @param LoggerInterface|null $logger                   Optional: PSR-3 Logger
     */
    public function __construct( \PDO $pdo, $permissions_table, LoggerInterface $logger = null )
    {
        $this->setLogger( $logger ?: new NullLogger );

        // Prerequisites
        $this->permissions_table       = $permissions_table;

        // Read pages and allowed roles
        $sql =  "INSERT INTO {$this->permissions_table}
        (permission_name, permission_description, info )
        VALUES
        (:name, :description, :info)";

        // Prepare business
        $this->stmt = $pdo->prepare( $sql );
    }


    /**
     * @return bool
     */
    public function __invoke( array $permission )
    {
        $permission = array_merge([ 'info' => null, ], $permission);

        $result = $this->stmt->execute( $permission );

        if ($result):
            $this->logger->info("Added new permission", $permission);
        else:
            $this->logger->error("Could not add new permission", $permission);
        endif;

        return $result;
    }
}
