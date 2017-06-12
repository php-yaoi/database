<?php

namespace Yaoi\Database\Tests\PHPUnit\Definition;


use Yaoi\Database\Definition\Column;
use Yaoi\Test\PHPUnit\TestCase;

class ColumnTest extends TestCase
{
    public function testNullCast()
    {
        $this->assertSame(null, Column::castField(null, Column::INTEGER));
        $this->assertSame(0, Column::castField(null, Column::INTEGER | Column::NOT_NULL));
        $this->assertSame('', Column::castField(null, Column::STRING | Column::NOT_NULL));
    }
}