<?php
/*
 * This file contains functions in global namespace
 * used as aliases of Dumpf class for simple usage
 */

/**
 * Function for testing, like breakpoint somewhere
 */
function _stop() {
    echo random_int(1000, 10000);
    die;
}

/**
 * Alias for echo() function
 */
function _e($str) {
    echo($str);
}

/**
 * Alias for echo() function and call exit() at last
 */
function _ex($str) {
    echo($str);
    exit;
}

/**
* Alias for exit function
*/
function _x() {
    exit;
}

/**
 * Alias for dumpf() with clean output buffer and call exit() at last
 */
function _dcx() {
    while(ob_end_clean());
    forward_static_call_array(['Morgo\Dumpf', 'dumpf'], func_get_args());
    exit();
}

/**
 * Alias for dumpf() with call exit() at last
 */
function _dx() {
    forward_static_call_array(['Morgo\Dumpf', 'dumpf'], func_get_args());
    exit();
}

/**
 * Alias for dumpf()
 */
function _d(){
    forward_static_call_array(['Morgo\Dumpf', 'dumpf'], func_get_args());
}

/**
 * Call var_dump function wrapped with <pre> tag
 */
function pre_dump(){
    echo('<pre>');
    call_user_func_array("var_dump", func_get_args());
    echo('</pre>');
}

/**
 * Dumpf to string
 */
function _sd() {
    ob_start();
    forward_static_call_array(['Morgo\Dumpf', 'dumpf'], func_get_args());
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}


 /**
  * Alias for DumpfPair::dump()
  */
 function _dp(){
     forward_static_call_array(['Morgo\DumpfPair', 'dump'], func_get_args());
 }

/**
 * Alias for dumpf_pairs() with call exit() at last
 */
function _dpx(){
    forward_static_call_array(['Morgo\DumpfPair', 'dump'], func_get_args());
    exit();
}
