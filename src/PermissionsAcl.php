<?php
namespace Germania\Permissions;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class PermissionsAcl
{

    /**
     * @var \PDOStatement
     */
    public $stmt;

    /**
     * @var LoggerInterface
     */
    public $logger;

    /**
     * @var string
     */
    public $permissions_table = "permissions";

    /**
     * @var string
     */
    public $permissions_roles_table = "permissions_roles";

    /**
     * @var Seperator string for roles in SELECT statement
     */
    protected $separator = ",";


    /**
     * @param \PDO                 $pdo                      PDO instance
     * @param string               $permissions_table        Permissions table name
     * @param string               $permissions_roles_table  Permissions and roles assignments table
     * @param LoggerInterface|null $logger                   Optional: PSR-3 Logger
     */
    public function __construct( \PDO $pdo, $permissions_table, $permissions_roles_table, LoggerInterface $logger = null )
    {
        // Prerequisites
        $this->permissions_table       = $permissions_table;
        $this->permissions_roles_table = $permissions_roles_table;
        $this->logger = $logger      ?: new NullLogger;

        // Read pages and allowed roles
        $sql =  "SELECT
        P.permission_name AS task,
        GROUP_CONCAT(Pmm.role_id SEPARATOR '{$this->separator}') AS roles

        FROM {$this->permissions_table} P
        LEFT JOIN {$this->permissions_roles_table} Pmm
        ON P.id = Pmm.permission_id

        GROUP BY task";

        // Prepare business
        $this->stmt = $pdo->prepare( $sql );
    }


    /**
     * @return array
     */
    public function __invoke(  )
    {
        $this->stmt->execute();

        // Fetch results; explode arrays
        $acl = array_map(function( $roles ) {
            return array_filter(explode($this->separator, $roles));
        }, $this->stmt->fetchAll( \PDO::FETCH_UNIQUE | \PDO::FETCH_OBJ | \PDO::FETCH_COLUMN));

        return $acl;
    }
}
