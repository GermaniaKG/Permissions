<?php
namespace Germania\Permissions;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class PdoAddPermission
{

    use LoggerAwareTrait;

    /**
     * @var \PDO
     */
    public $pdo;

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
        $this->pdo                     = $pdo;
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
     * @return int Last Insert ID or FALSE when something errored
     * @throws PermissionNameExistsException On duplicate name
     */
    public function __invoke( array $permission )
    {
        $permission = array_merge([ 'info' => null, ], $permission);

        try {
            $result = $this->stmt->execute( $permission );
            if ($result):
                $this->logger->info("Added new permission", $permission);
                return (int) $this->pdo->lastInsertId();
            endif;

            $this->logger->error("Could not execute 'Add permission' statement", $permission);
            return false;
        }
        catch( \PDOException $e )
        {
            // SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry ...
            if ($e->getCode() == 23000):
                throw new PermissionNameExistsException("Permission name already exists", 23000, $e);
            endif;

            throw $e;
        }

    }
}
