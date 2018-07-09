<?php
namespace Germania\Permissions;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundException;

class PdoAllPermissions implements \Countable, \IteratorAggregate, ContainerInterface
{

    use LoggerAwareTrait;

    /**
     * @var \PDOStatement
     */
    public $stmt;

    /**
     * @var Array
     */
    public $permissions = array();

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
        $this->setLogger( $logger ?: new NullLogger );

        // Prerequisites
        $this->permissions_table       = $permissions_table;
        $this->permissions_roles_table = $permissions_roles_table;

        // Read pages and allowed roles
        $sql =  "SELECT
        -- Select name twice here because of UNIQUE
        P.permission_name AS name,
        P.id,
        P.permission_name AS name,
        P.permission_description AS description,
        P.info AS info,
        GROUP_CONCAT(Pmm.role_id ORDER BY role_id ASC SEPARATOR '{$this->separator}') AS assigned_roles

        FROM {$this->permissions_table} P
        LEFT JOIN {$this->permissions_roles_table} Pmm
        ON P.id = Pmm.permission_id

        GROUP BY name";

        // Prepare business
        $this->stmt = $pdo->prepare( $sql );

        $this->stmt->execute();

        $this->permissions = $this->stmt->fetchAll( \PDO::FETCH_UNIQUE | \PDO::FETCH_ASSOC);
    }


    /**
     * @inheritDoc
     */
    public function has( $name )
    {
        return array_key_exists($name, $this->permissions);
    }


    /**
     * @inheritDoc
     * @throws PermissionNotFoundException
     */
    public function get( $name )
    {
        if ($this->has($name))
            return $this->permissions[ $name ];
        throw new PermissionNotFoundException("There is no permission '$name'");
    }


    /**
     * @inheritDoc
     * @return int
     */
    public function count()
    {
        return count( $this->permissions );
    }

    /**
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator( $this->permissions );
    }
}
