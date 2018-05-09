<?php
/**
 * Rich debugger function
 * Author: Antonín Neumann
 * Version: 0.9.3 (10-2015)
 *
 * Change log:
 *  - added _e() method alias for echo();
 *
 */

if (!defined('TAB')) {
    define('TAB', "\t");
}

/**
 * Alias for echo() function
 */
function _e($str){
    echo($str);
}

/**
 * Alias for echo() function and call exit() at last
 */
function _ex($str){
    echo($str);
    exit;
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
 * Alias for dumpf() with clean output buffer and call exit() at last
 */
function _dcx() {
    while(ob_end_clean());
    call_user_func_array("dumpf", func_get_args());
    exit();
}

/**
 * Alias for dumpf() with call exit() at last
 */
function _dx(){
    call_user_func_array("dumpf", func_get_args());
    exit();
}

/**
 * Alias for dumpf()
 */
function _d(){
    call_user_func_array("dumpf", func_get_args());
}

/**
 * Dumpf to strign
 */
function _sd() {
    ob_start();
    call_user_func_array("dumpf", func_get_args());
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

/**
 * Alias for dumpf_pairs()
 */
function _dp(){
    call_user_func_array("dumpf_pairs", func_get_args());
}

/**
 * Alias for dumpf_pairs() with call exit() at last
 */
function _dpx(){
    call_user_func_array("dumpf_pairs", func_get_args());
    exit();
}

/**
 * Alias for dumpf_pairs() with call exit() at last
 */
function dumpfx_pairs(){
    call_user_func_array("dumpf_pairs", func_get_args());
    exit();
}

/**
 * Alias for dumpf() with call exit() at last
 */
function dumpfx(){
    call_user_func_array("dumpf", func_get_args());
    exit();
}

function set_utf8_encoding_header() {
    // set only if not set yet
    if (!headers_sent()) {
        header('Content-Type: text/html; charset=utf-8');
    }
}

/**
 * Print rich value of variable(s)
 * Prvni parametr je jmeno a druhy promenna
 * Vypise jmeno = promenna
 * @param mixed $expression (one or more)
 */
function dumpf_pairs() {
    set_utf8_encoding_header();
    if(func_num_args() <= 0) {
        return;
    }
    $broken = false;
    echo("<pre>");
    foreach (func_get_args() as $k => $arg) {
        if(!$broken && $k%2 == 0){
            if(is_string($arg)){
                echo("<span style='color: blue;'>" . $arg."</span> = ");
            }else {
                echo("<span style='color: red;'>!-- Pairs dumpf is broken. --!</span>\n");
                $broken = true;
                dumpf_var($arg);
            }
        }else {
            dumpf_var($arg);
        }
    }
    echo("</pre>");
}

/**
 * Print rich value of variable(s)
 * @param mixed $expression (one or more)
 */
function dumpf() {
    set_utf8_encoding_header();
    if(func_num_args() <= 0) {
        return;
    }
    echo("<pre style='font-size: 110%; position: relative; z-index: 999;'>");
    call_user_func_array("dumpf_var", func_get_args());
    echo("</pre>");
}

function dumpf_var(){
    foreach (func_get_args() as $arg) {
        if(is_array($arg)) {
            dumpf_array($arg, 1);
        }
        else if(is_object($arg)) {
            dumpf_object($arg, 1);
        }
        else {
            if(is_null($arg)) {
                echo("<strong>NULL</strong>\n");
            }
            else if(is_string($arg)){
                echo(gettype($arg)." (".strlen($arg)."): <strong>".(empty($arg)?"EMPTY":$arg)."</strong>\n");
            }
            else {
                echo(gettype($arg).": <strong>".dumpf_str_bool($arg)."</strong>\n");
            }
        }
    }
}

/**
 * Print all array value (recursively)
 *
 * @param array $arr Array to print
 * @param int $level Level of nesting
 * @param string $key (optional) Jmeno promenne obsahujici zanorene pole
 */
function dumpf_array($arr, $level, $key = NULL) {
    $tab = "";
    for($i = 1; $i < $level; $i++){
        $tab .= "\t";
    }
    $arr_count = count($arr);
    echo($tab);
    if(isset($key)){
        echo("[".$key."] => ");
    }
    echo("array (" . $arr_count . ") {");
    if($arr_count > 0){
        echo("\n");
        foreach($arr as $key => $item){
            if(is_array($item)) {
                dumpf_array($item, $level+1, $key);
            }
            else if(is_object($item)){
                dumpf_object($item, $level+1, $key);
            }
            else {
                echo($tab."\t[<em>".$key."</em>] => "
                        . "<strong>".dumpf_str_bool($item)."</strong> "
                        . "(<span class='dumpf variable-type'>".gettype($item)."</span>)\n");
            }
        }
        echo($tab."}\n");
    }else {
        echo("EMPTY");
        echo("}\n");
    }
}

/**
 * Print all object property (recursively)
 *
 * @param object $obj Object (class) to print
 * @param int $level Level of nesting
 * @param string $key (optional) Jmeno promenne obsahujici zanoreny objekt
 */
function dumpf_object($obj, $level, $key = NULL) {
    $special_object_printers = array(
        "Dibi\DateTime" => function($obj, $tab) {
            echo($tab."\t<em>date: </em><strong>".$obj->format("d. m. Y H:i:s")."</strong>\n");
            echo($tab."\t<em>timezone: </em><strong>".$obj->format("e P")."</strong>\n");
            echo($tab."\t<em>timestap: </em><strong>".$obj->format("U")."</strong>\n");
        }
    );

    $tab = '';
    for($i = 1; $i < $level; $i++){
        $tab .= TAB;
    }
    echo($tab);
    if(isset($key)){
        echo('['.$key.'] => ');
    }

    $reflect = new ReflectionClass($obj);
    $properties = $reflect->getProperties();

    // blue: #016FD9, red: #D42A2A, yellow: #f1c40f
    echo("object <strong style='color: #D42A2A;' class='dumpf object-name'>" . get_class($obj) . "</strong> {");

    if(array_key_exists(get_class($obj), $special_object_printers)) {
        echo("\n");
        $special_object_printers[get_class($obj)]($obj, $tab);
        echo($tab."}\n");
    }
    else if(count($properties) > 0){
        echo("\n");
        foreach($properties as $item){
            $var_name = $item->getName();
            $var = $reflect->getProperty($var_name);
            $var->setAccessible(true);
            $var_value = $var->getValue($obj);

            $var_scope = "";
            if($var->isPrivate()){
                $var_scope = ":<span style='color: red;'>private</span>";
            }else if($var->isProtected()){
                $var_scope = ":<span style='color: orange;'>protected</span>";
            }else if($var->isStatic()){
                $var_scope = ":<span style='color: blue;'>static</span>";
            }else {
                $var_scope = ":<span style='color: green;'>public</span>";
            }

            if(is_object($var_value)){
                dumpf_object($var_value, $level+1, $var_name);
            }else if(is_array($var_value)){
                dumpf_array($var_value, $level+1, $var_name);
            }else {
                echo($tab."\t[<em>".$item->getName().$var_scope."</em>] => "
                        . "<strong>".dumpf_str_bool($var_value)."</strong> "
                        . "(".gettype($var_value).")\n");
            }
        }
        echo($tab . '}' . PHP_EOL);
    }
    else {
        echo(PHP_EOL);
        dumpf_array($obj, $level+1);
        // $methods = get_class_methods($obj);
        // foreach ($methods as $method) {
        //     echo($tab . TAB . $method . '()' . PHP_EOL);
        // }
        echo($tab.'}' . PHP_EOL);
    }
}

/**
 * String representation of boolean variable or variable $var
 *
 * @param mixed $var
 * @return mixed String if variable is type of boolean or $var
 */
function dumpf_str_bool($var){
    $is_bool = is_bool($var);
    if($is_bool && $var){
        return "TRUE";
    }else if($is_bool) {
        return "FALSE";
    }else if(is_null($var) || !isset($var)){
        return "NULL";
    }else if(is_string($var) && empty($var)){
        return "EMPTY";
    }else {
        return $var;
    }
}


/*
=== BREAK POINTS ===
*/

/** Alias pro lastbreakp() */
function bpx($x = NULL) { lastbreakp($x); }
/** Alias pro lastbreakp() */
function breakpx($x = NULL) { lastbreakp($x); }
/** Funkce breakp() následovaná příkazem exit(); */
function lastbreakp($x = NULL){
    exit(breakp($x, true));
}

/** Alias pro breakp() */
function bp($x = NULL, $pre = false) { breakp($x, $pre); }
/** Alias pro breakp() */
function breakpoint($x = NULL, $pre = false) { breakp($x, $pre); }
/**
 * Vytvoří v aplikaci breakpoint s číslem a výpisem stringu
 *
 * @staticvar int $count pořadové číslo breakpointu
 * @param mixed $x Obsah zprávy, která se vypíše
 * @param bool $pre Pokud je TRUE vypíše se celý breakpoint mezi tagy [pre]
 */
function breakp($x = NULL, $pre = false){
    static $count = 1;

    $opt = array(
        1 => "Asgard",
        "Goa'uld",
        "Tok'ra",
        "Jaffa",
        "Ori",
        "Nox",
        "Furling",
        "Replikator",
        "Wraith",
        "Athosian",
        "Unas"
    );

    if(is_numeric($x)){
        intval($x);
        $x = ($x % count($opt)) + 1;
    }else if(is_string($x)){
        $x = (strlen($x) % count($opt)) + 1;
    }

    $opt[$x] == NULL ? $str = "HERE" : $str = $opt[$x];
    if($pre){
        printf("<pre>[BP-%d]: %s\n</pre>",$count++, $str);
    } else  {
        printf("[BP-%d]: %s\n<br>",$count++, $str);
    }
    return $count;
}
?>
