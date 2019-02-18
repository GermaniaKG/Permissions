# Germania KG · Permissions


[![Packagist](https://img.shields.io/packagist/v/germania-kg/permissions.svg?style=flat)](https://packagist.org/packages/germania-kg/permissions)
[![PHP version](https://img.shields.io/packagist/php-v/germania-kg/permissions.svg)](https://packagist.org/packages/germania-kg/permissions)
[![Build Status](https://img.shields.io/travis/GermaniaKG/Permissions.svg?label=Travis%20CI)](https://travis-ci.org/GermaniaKG/Permissions)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/GermaniaKG/Permissions/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/Permissions/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/GermaniaKG/Permissions/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/Permissions/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/GermaniaKG/Permissions/badges/build.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/Permissions/build-status/master)



## Installation

```bash
$ composer require germania-kg/permissions
```

**MySQL users** may install the tables *permissions* and *permissions\_roles* using `install.sql.txt` in `sql/` directory.


## Usage

```php
<?php
use Germania\Permissions\PermissionsAcl;

// Have your PDO and optional a PSR-3 Logger at hand
$pdo    = new PDO;
$logger = new Monolog;

// Pass PDO and table names,
// optionally with PSR-3 Logger
$perms = new PermissionsAcl( $pdo, "permissions", "permissions_roles" );
$perms = new PermissionsAcl( $pdo, "permissions", "permissions_roles", $logger );

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




## Development

```bash
$ git clone https://github.com/GermaniaKG/Permissions.git
$ cd Permissions
$ composer install
```

Setup MySQL tables *permissions* and *permissions_roles* as in `sql/install.sql.txt`. 

## Unit tests

Either copy `phpunit.xml.dist` to `phpunit.xml` and adapt to your needs, or leave as is. Run [PhpUnit](https://phpunit.de/) test or composer scripts like this:

```bash
$ composer test
# or
$ vendor/bin/phpunit
```

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