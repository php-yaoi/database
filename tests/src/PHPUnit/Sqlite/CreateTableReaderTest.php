<?php

namespace Yaoi\Database\Tests\PHPUnit\Sqlite;


use Yaoi\Database\Database;
use Yaoi\Database\Tests\Helper\Entity\Host;
use Yaoi\Database\Tests\Helper\Entity\Session;
use Yaoi\Log;
use Yaoi\Test\PHPUnit\TestCase;

class CreateTableReaderTest extends TestCase
{

    /**
     * @var Database
     */
    protected $database;

    private $dbFileName;

    public function setUp()
    {
        $this->dbFileName = sys_get_temp_dir() . '/create-table-reader-test.sqlite';
        $this->database = new Database('sqlite:///' . $this->dbFileName);
    }

    public function testParser()
    {
        Session::bindDatabase($this->database, true);
        Host::bindDatabase($this->database, true);

        Session::table()->migration()->setLog(Log::getInstance())->apply();

        $actualDefinition = $this->database->getUtility()->getTableDefinition(Session::table()->schemaName);
        $expectedDefinition = Session::table();

        $this->assertSame((string)$expectedDefinition->getCreateTable(), (string)$actualDefinition->getCreateTable());


    }

    public function tearDown()
    {
        unset($this->database);
        unlink($this->dbFileName);
    }
}