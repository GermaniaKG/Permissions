<?php
namespace Germania\Permissions;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class PdoAssignRoleToPermission
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
    public $permissions_roles_table = "permissions_roles";

    /**
     * @var Seperator string for roles in SELECT statement
     */
    protected $separator = ",";


    /**
     * @param \PDO                 $pdo                      PDO instance
     * @param string               $permissions_roles_table  Permissions/Roles table name
     * @param LoggerInterface|null $logger                   Optional: PSR-3 Logger
     */
    public function __construct( \PDO $pdo, $permissions_roles_table, LoggerInterface $logger = null )
    {
        $this->setLogger( $logger ?: new NullLogger );

        // Prerequisites
        $this->pdo                     = $pdo;
        $this->permissions_roles_table = $permissions_roles_table;

        // Read pages and allowed roles
        $sql =  "INSERT INTO {$this->permissions_roles_table}
        (permission_id, role_id )
        VALUES
        (:permission_id, :role_id)";

        // Prepare business
        $this->stmt = $pdo->prepare( $sql );
    }


    /**
     * @return int Last Insert ID or FALSE when something errored
     * @throws PermissionNameExistsException On duplicate name
     */
    public function __invoke( $permission_id, $role_id )
    {
        try {
            $info = [
                'permission_id' => $permission_id,
                'role_id' => $role_id
            ];

            $result = $this->stmt->execute( $info );
            if ($result):
                $this->logger->info("Assigned new role to permission", $info);
                return (int) $this->pdo->lastInsertId();
            endif;

            $this->logger->error("Could not execute 'Assign role to permission' statement", $info);
            return false;
        }
        catch( \PDOException $e )
        {
            // SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry ...
            if ($e->getCode() == 23000):
                throw new RolePermissionAssignmentExistsException("Role/Permission assignment already exists", 23000, $e);
            endif;

            throw $e;
        }

    }
}
