<?php
namespace Morgo;

/**
 * Pretty dump variables
 */
class DumpfPair
{
    /**
     * [dumpf description]
     * @return void
     */
    public static function dump() {
        Utils::setUtf8EncodingHeader();
        if(func_num_args() <= 0) {
            return;
        }
        $broken = false;
        echo('<pre>');
        foreach (func_get_args() as $k => $arg) {
            if (!$broken && $k%2 == 0) {
                if (is_string($arg)) {
                    echo('<span style="color: blue;">' . $arg . '</span> = ');
                } else {
                    echo('<span style="color: red;">!-- Pairs dumpf is broken. --!</span>' . PHP_EOL);
                    $broken = true;
                    Dumpf::dumpVar($arg);
                }
            } else {
                Dumpf::dumpVar($arg);
            }
        }
        echo('</pre>');
    }
}
