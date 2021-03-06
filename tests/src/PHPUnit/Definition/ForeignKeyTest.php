<?php

namespace Yaoi\Database\Tests\PHPUnit\Definition;

use Yaoi\Database\Definition\ForeignKey;
use Yaoi\Database\Tests\Helper\Entity\Session;
use Yaoi\Database\Tests\Helper\Entity\SessionTag;
use Yaoi\Database\Tests\Helper\Entity\Tag;
use Yaoi\Test\PHPUnit\TestCase;

class ForeignKeyTest extends TestCase
{

    /**
     * Local and reference columns count must match, otherwise exception is thrown
     *
     * @see ForeignKey::__construct
     * @expectedException \Yaoi\Database\Definition\Exception
     * @expectedExceptionCode \Yaoi\Database\Definition\Exception::FK_COUNT_MISMATCH
     */
    public function testColumnMismatch() {
        $localColumns = array(SessionTag::columns()->sessionId, SessionTag::columns()->tagId);
        $referenceColumns = array(Session::columns()->id);
        new ForeignKey($localColumns, $referenceColumns);
    }

    /**
     * If FK name is longer than 64 chars, it is hashed with MD5
     * @see ForeignKey::getName
     */
    public function testLongName() {
        $localColumns = array(SessionTag::columns()->sessionId, SessionTag::columns()->tagId);
        $referenceColumns = array(Session::columns()->id, Tag::columns()->id);
        $foreignKey = new ForeignKey($localColumns, $referenceColumns);
        $this->assertSame('kaac7b385eb0381570e1584bcdd91e6f5', $foreignKey->getName());
    }
}