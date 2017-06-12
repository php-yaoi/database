<?php

use Yaoi\Log;
use Yaoi\Migration;
use Yaoi\Storage\PhpVar;
use Yaoi\Database\Database;


Database::register('mysqli://root@localhost/test1', Yaoi\Service::PRIMARY);
Database::register(\Yaoi\Service::PRIMARY, 'test_mysqli');
Database::register('pgsql://postgres@localhost/travis_ci_test', 'test_pgsql');
Database::register('pdo-pgsql://postgres@localhost/travis_ci_test', 'test_pdo_pgsql');
Log::register('stdout', Yaoi\Service::PRIMARY);
error_reporting(E_ALL);
ini_set('display_errors', 1);

Migration\Manager::register(function () {
    $dsn = new Migration\Settings();
    $dsn->storage = new PhpVar();
    return $dsn;
}, Yaoi\Service::PRIMARY);
