<?php

namespace Yaoi\Database\Tests\PHPUnit\Entity\Migration;


use Yaoi\Database\Tests\Helper\CheckAvailable;

class PdoPgsqlTest extends PgsqlTest
{

    public function setUp()
    {
        $this->database = CheckAvailable::getPdoPgsql();
    }


}