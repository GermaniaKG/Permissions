#Germania\Permissions


[![Build Status](https://travis-ci.org/GermaniaKG/Permissions.svg?branch=master)](https://travis-ci.org/GermaniaKG/Permissions)
[![Code Coverage](https://scrutinizer-ci.com/g/GermaniaKG/Permissions/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/Permissions/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/GermaniaKG/Permissions/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/Permissions/?branch=master)


##Installation

```bash
$ composer require germania-kg/permissions
```

**MySQL users** may install the tables *permissions* and *permissions\_roles* using `install.sql.txt` in `sql/` directory.


##Usage

```php
<?php
use Germania\Permissions\PermissionsAcl;

// Have your PDO and optional a PSR-3 Logger at ready
$pdo    = new PDO;
$logger = new Monolog;

$perms = new PermissionsAcl( $pdo );
$perms = new PermissionsAcl( $pdo, null, null, $logger );

// Use custom names for tables:
$perms = new PermissionsAcl( $pdo, 'tasks', 'tasks_roles' );

// Use Callable
$acl = $perms();
```



```php
print_r( $acl );

// Keys are permissions;
// Element arrays are roles
(
    [bar] => Array
        (
            [0] => 1
        )

    [foo] => Array
        (
            [0] => 1
            [1] => 2
        )

    [quc] => Array
        (
        )

)
```



##Development and Testing

First, grab your clone:

```bash
$ git clone git@github.com:GermaniaKG/Permissions.git permissions
$ cd permissions
$ composer install
$ cp phpunit.xml.dist phpunit.xml
```

Develop using `develop` branch, using [Git Flow](https://github.com/nvie/gitflow).   

Setup MySQL tables *permissions* and *permissions_roles* as in `sql/install.sql.txt`. 

In `phpunit.xml`, edit the database credentials:

```xml
<php>
	<var name="DB_DSN"    value="mysql:host=localhost;dbname=test;charset=utf8" />
	<var name="DB_USER"   value="root" />
	<var name="DB_PASSWD" value="" />
	<var name="DB_DBNAME" value="test" />
	<var name="DB_SETUP"  value="sql/install.sql.txt" />
</php>
```


Go to project root and issue `phpunit`.

