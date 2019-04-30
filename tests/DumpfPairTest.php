<?php
declare(strict_types = 1);

namespace Morgo;

use PHPUnit\Framework\TestCase;

final class DumpfPairTest extends TestCase
{
    public function testAssocArrayAsParameter() {
        $x = ['This is int' => 123];
        $expect = '<pre><span style="color: blue;">This is int</span> = integer: <strong>123</strong>'.PHP_EOL.'</pre>';

        ob_start();
        DumpfPair::dump($x);
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($expect, $output);
    }

    public function testAssocArrayAsParameterMultipleValues() {
        $x = [
            'This is int' => 123,
            'This is boolean' => false,
        ];
        $expect = '<pre><span style="color: blue;">This is int</span> = integer: <strong>123</strong>'.PHP_EOL
        .'<span style="color: blue;">This is boolean</span> = boolean: <strong>FALSE</strong>'.PHP_EOL
        .'</pre>';

        ob_start();
        DumpfPair::dump($x);
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($expect, $output);
    }

    public function testNotAssocArrayAsParameter() {
        $x = [123, true];
        $expect = '<pre><span style="color: blue;">0</span> = integer: <strong>123</strong>'.PHP_EOL
        .'<span style="color: blue;">1</span> = boolean: <strong>TRUE</strong>'.PHP_EOL
        .'</pre>';

        ob_start();
        DumpfPair::dump($x);
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertEquals($expect, $output);
    }
}
