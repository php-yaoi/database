<?php
namespace Yaoi\Database\Tests\PHPUnit;

use Yaoi\Database;
use Yaoi\Test\PHPUnit\TestCase;

class BindsTest extends TestCase
{
    public function setUp()
    {
        \Yaoi\Database\Tests\Helper\CheckAvailable::getMysqli();
    }

    public function testUnnamedBinds()
    {
        $db = Database\Database::getInstance('test_mysqli')->mock();

        $expected = 'SELECT 1, \'two\', NULL, 0.445453';
        $this->assertSame($expected, $db->query("SELECT ?, ?, ?, ?", 1, 'two', null, 0.445453)->skipAutoExecute()->build());
        $this->assertSame($expected, $db->query("SELECT ?, ?, ?, ?", array(1, 'two', null, 0.445453))->skipAutoExecute()->build());
        $this->assertSame($expected, $db->query("SELECT :one, :two, :three, :four",
            array('one' => 1, 'two' => 'two', 'three' => null, 'four' => 0.445453))->skipAutoExecute()->build());


        $expected = 'SELECT 1, 1, 1';
        $this->assertSame($expected, $db->query("SELECT :one, :one, :one",
            array('one' => 1, 'two' => 'two'))->skipAutoExecute()->build());

        $this->assertSame($expected, $db->query("SELECT :one, :one, :one",
            array('one' => 1))->skipAutoExecute()->build());


    }


    public function testDestruct()
    {
        return;
        $db = Database\Database::create(Database\Database::$instanceConfig['test_mysqli'])->query("SHOW TABLES");
        unset($db);


    }


}