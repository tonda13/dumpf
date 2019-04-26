<?php
/**
 * Originial file containing function of Pretty dump
 */

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
