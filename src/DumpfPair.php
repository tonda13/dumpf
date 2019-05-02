<?php
namespace Morgo;

/**
 * Pretty dump variables
 */
class DumpfPair
{
    /**
     * Prety dump variables with labels
     *
     * param array Key-Value pairs
     *
     * @return void
     */
    public static function dump() {
        Utils::setUtf8EncodingHeader();
        if(func_num_args() <= 0) {
            return;
        }

        echo('<pre>');
        foreach (func_get_args() as $k => $arg) {
            if (is_array($arg)) {
                foreach ($arg as $key => $value) {
                    echo('<span style="color: blue;">' . $key . '</span> = ');
                    Dumpf::dumpVar($value);
                }
            }
            else {
                echo('<span style="color: red;">!-- Wrong format --!</span>' . PHP_EOL);
            }
        }
        echo('</pre>');
    }
}
