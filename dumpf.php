<?php


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
