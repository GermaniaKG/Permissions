<?php
namespace Germania\Permissions;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class PdoUpdatePermissionRoles
{

    use LoggerAwareTrait;

    public $permissions;

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
     * @var string
     */
    public $permissions_roles_table = "permissions_roles";

    /**
     * @var Seperator string for roles in SELECT statement
     */
    protected $separator = ",";


    /**
     * @var PdoAssignRoleToPermission
     */
    public $assigner;

    /**
     * @var PdoAssignRoleToPermission
     */
    public $unassigner;



    /**
     * @param \PDO                 $pdo                      PDO instance
     * @param string               $permissions_table        Permissions table name
     * @param string               $permissions_roles_table  Permissions/Roles table name
     * @param LoggerInterface|null $logger                   Optional: PSR-3 Logger
     */
    public function __construct( \PDO $pdo, $permissions_table, $permissions_roles_table, LoggerInterface $logger = null )
    {
        $this->setLogger( $logger ?: new NullLogger );

        // Prerequisites
        $this->pdo                     = $pdo;
        $this->permissions_table       = $permissions_table;
        $this->permissions_roles_table = $permissions_roles_table;


        $this->assigner    = new PdoAssignRoleToPermission( $pdo, $permissions_roles_table, $logger);
        $this->unassigner  = new PdoUnassignRoleFromPermission( $pdo, $permissions_roles_table, $logger);
        $this->permissions = new PdoAllPermissions($pdo, $permissions_table, $permissions_roles_table, $logger);
    }


    /**
     * @return int Last Insert ID or FALSE when something errored
     * @throws PermissionNameExistsException On duplicate name
     */
    public function __invoke( $permission_name, $role_ids )
    {

        try {
            $permission = $this->permissions->get( $permission_name );
        }
        catch (PermissionNotFoundException $e) {
            throw $e;
        }


        $permission_id  = $permission['id'];
        $assigned_roles = explode( $this->separator, $permission['assigned_roles']);

        $assigner   = $this->assigner;
        $unassigner = $this->unassigner;

        $to_add = array_diff($role_ids, $assigned_roles);
        foreach( $to_add as $role_id):
            try {
                $assigner( $permission_id, $role_id);
            }
            catch (RolePermissionAssignmentExistsException $e) {
                // noop
            }
        endforeach;


        $to_remove = array_diff($assigned_roles, $role_ids);
        foreach( $to_remove as $role_id):
            $unassigner( $permission_id, $role_id);
        endforeach;

        return true;
    }
}
