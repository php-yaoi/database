<?php
/**
 * Created by PhpStorm.
 * User: vpoturaev
 * Date: 8/30/15
 * Time: 20:53
 */

namespace Yaoi\Database\Tests\PHPUnit\Definition;

use Yaoi\Database\Definition\ForeignKey;
use Yaoi\Database\Tests\Helper\Entity\Session;
use Yaoi\Test\PHPUnit\TestCase;

class TableTest extends TestCase
{
    /**
     * If table column was set as a reference to other table column, you can get associated foreign key
     *
     * @see Table::getForeignKeyByColumn
     */
    public function testGetForeignKeyByColumn() {
        // has reference
        $this->assertInstanceOf(
            ForeignKey::className(),
            Session::table()->getForeignKeyByColumn(Session::columns()->hostId)
        );

        // no reference
        $this->assertNull(
            Session::table()->getForeignKeyByColumn(Session::columns()->endedAt)
        );
    }

}