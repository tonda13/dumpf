<?php
namespace Morgo;

use PHPUnit\Framework\TestCase;

class DumpfTest extends TestCase
{
    public function testSimpleSuccess() {
        $this->assertTrue(TRUE);
    }

    public function testSimpleString(): void {
        Dumpf::dumpf('Hello World');
    }
}
