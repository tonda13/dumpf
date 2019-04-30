<?php
declare(strict_types = 1);

namespace Morgo;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Morgo\Dumpf;

final class DumpfTest extends TestCase
{
    /**
     * Test conversion of some special variables to their string representation
     *
     * @return void
     */
    public function testSpecialToString() : void {
        Dumpf::dump('a');
        $conversionResult = $this->invokeStaticMethod(Dumpf::class, 'specialToString', [true]);
        $this->assertEquals(Dumpf::TRUE_STRING, $conversionResult);

        $conversionResult = $this->invokeStaticMethod(Dumpf::class, 'specialToString', [false]);
        $this->assertEquals(Dumpf::FALSE_STRING, $conversionResult);

        $conversionResult = $this->invokeStaticMethod(Dumpf::class, 'specialToString', [null]);
        $this->assertEquals(Dumpf::NULL_STRING, $conversionResult);

        $conversionResult = $this->invokeStaticMethod(Dumpf::class, 'specialToString', ['']);
        $this->assertEquals(Dumpf::EMPTY_STRING, $conversionResult);
    }

    /**
     * Call protected/private method of a class.
     *
     * @param string $className  Class name that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeStaticMethod(string $className, string $methodName, array $parameters = array()) {
        $reflection = new ReflectionClass($className);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs(null, $parameters);
    }
}
