<?php

namespace Yaoi\Database\Tests\PHPUnit\Sqlite;

use Yaoi\Database;
use Yaoi\Database\Definition\Table;
use Yaoi\Database\Tests\PHPUnit\TestUnified;


class SqliteTest extends TestUnified
{
    protected $createTable1 = <<<SQL
Create TABLE IF NOT EXISTS test_def (
  id1 INTEGER NOT NULL,
  id2 INTEGER NOT NULL DEFAULT 1,
  name VARCHAR(10) NOT NULL DEFAULT 'Jon Snow',
  address CHAR(10),
  Primary KEY(id1, id2)
);
SQL;


    protected $createTableStatement = <<<SQL
CREATE TABLE `test_indexes` (
 `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
 `name` varchar(255) NOT NULL,
 `uni_one` INTEGER DEFAULT NULL,
 `uni_two` INTEGER DEFAULT NULL,
 `default_null` float DEFAULT NULL,
 `updated` timestamp DEFAULT NULL,
 `ref_id` INTEGER NOT NULL,
 `r_one` INTEGER DEFAULT NULL,
 `r_two` INTEGER DEFAULT NULL
);
CREATE UNIQUE INDEX `test_indexes_unique_uni_one_uni_two` ON `test_indexes` (`uni_one`, `uni_two`);
CREATE INDEX `test_indexes_key_name` ON `test_indexes` (`name`);

SQL;

    protected function autoIncrementTest(Table $def)
    {
        // TODO: support autoincrement
    }


    private $dbFileName;

    public function setUp()
    {
        $this->dbFileName = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'testSqlite.sqlite';
        $this->db = new Database\Database('sqlite:///' . $this->dbFileName);

    }

    public function tearDown()
    {
        $this->db->disconnect();
        if (file_exists($this->dbFileName)) {
            unlink($this->dbFileName);
        }
    }

    protected function columnsTest2(Table $def)
    {
        return;
    }


    protected $testCreateIndexesAlterExpected = <<<SQL
DROP INDEX 'test_indexes_unique_uni_one_uni_two';
DROP INDEX 'test_indexes_key_name';
ALTER TABLE `test_indexes` RENAME TO _temp_table;
CREATE TABLE `test_indexes` (
 `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
 `name` varchar(255) NOT NULL,
 `uni_one` INTEGER DEFAULT NULL,
 `uni_two` INTEGER DEFAULT NULL,
 `default_null` float DEFAULT NULL,
 `updated` timestamp DEFAULT NULL,
 `new_field` char(15) NOT NULL DEFAULT 'normal'
);
CREATE UNIQUE INDEX `test_indexes_unique_updated` ON `test_indexes` (`updated`);
INSERT INTO `test_indexes` (`id`, `name`, `uni_one`, `uni_two`, `default_null`, `updated`) SELECT `id`, `name`, `uni_one`, `uni_two`, `default_null`, `updated` FROM _temp_table;
DROP TABLE _temp_table;

SQL;

    protected $testCreateTableAfterAlter = <<<SQL
CREATE TABLE `test_indexes` (
 `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
 `name` varchar(255) NOT NULL,
 `uni_one` INTEGER DEFAULT NULL,
 `uni_two` INTEGER DEFAULT NULL,
 `default_null` float DEFAULT NULL,
 `updated` timestamp DEFAULT NULL,
 `new_field` char(15) NOT NULL DEFAULT 'normal'
);
CREATE UNIQUE INDEX `test_indexes_unique_updated` ON `test_indexes` (`updated`);

SQL;


    protected $testDefaultValueConsistency = <<<LOG
# Apply, table test_columns (Yaoi\Database\Tests\Helper\Entity\TestColumns) requires migration
CREATE TABLE `test_columns` (
 `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
 `int_column` INTEGER NOT NULL DEFAULT '2',
 `int8_column` INTEGER NOT NULL DEFAULT '2',
 `float_column` float NOT NULL DEFAULT '1.33',
 `string_column` varchar(255) NOT NULL DEFAULT '11'
);
# OK
# Apply, table test_columns (Yaoi\Database\Tests\Helper\Entity\TestColumns) is up to date

LOG;



}