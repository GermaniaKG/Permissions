<?php
namespace Germania\Permissions;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class PdoUnassignRoleFromPermission
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
        $sql =  "DELETE FROM {$this->permissions_roles_table}
        WHERE permission_id = :permission_id
        AND role_id = :role_id
        LIMIT 1";

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
                $this->logger->info("Unassigned role from permission", $info);
                return true;
            endif;

            $this->logger->error("Could not execute 'Unassign role from permission' statement", $info);
            return false;
        }
        catch( \PDOException $e )
        {
            throw $e;
        }

    }
}
