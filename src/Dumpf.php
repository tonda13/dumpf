<?php
namespace Morgo;

/**
 * Pretty dump variables
 */
class Dumpf
{
    /**
     * Tab character constant
     * @var string
     */
    const TAB = "\t";

    /**
     * New line contant
     * @var string
     */
    const EOL = PHP_EOL;

    const TRUE_STRING = 'TRUE';
    const FALSE_STRING = 'FALSE';
    const NULL_STRING = 'NULL';
    const EMPTY_STRING = 'EMPTY';

    public static function dump() : void {
        Utils::setUtf8EncodingHeader();
        if (func_num_args() <= 0) {
            return;
        }
        echo("<pre style='font-size: 110%; position: relative; z-index: 999;'>");
        forward_static_call_array(['Morgo\Dumpf', 'dumpVar'], func_get_args());
        echo("</pre>");
    }

    /**
     * Start processing all variables to pretty dump
     *
     * @return void
     */
    public static function dumpVar() : void {
        foreach (func_get_args() as $arg) {
            if (is_array($arg)) {
                self::dumpArray($arg, 1);
            } elseif (is_object($arg)) {
                self::dumpObject($arg, 1);
            } else {
                if (is_null($arg)) {
                    echo("<strong>NULL</strong>".self::EOL);
                } elseif (is_string($arg)) {
                    echo(
                        gettype($arg)." (".strlen($arg)."): <strong>".(empty($arg)?"EMPTY":$arg)."</strong>".self::EOL
                    );
                } else {
                    echo(gettype($arg).": <strong>".self::specialToString($arg)."</strong>".self::EOL);
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
    private static function dumpArray($arr, $level, $key = null) {
        $tab = "";
        for ($i = 1; $i < $level; $i++) {
            $tab .= self::TAB;
        }
        $arr_count = count($arr);
        echo($tab);
        if (isset($key)) {
            echo("[".$key."] => ");
        }
        echo("array (" . $arr_count . ") {");
        if ($arr_count > 0) {
            echo(self::EOL);
            foreach ($arr as $key => $item) {
                if (is_array($item)) {
                    self::dumpArray($item, $level+1, $key);
                } elseif (is_object($item)) {
                    self::dumpObject($item, $level+1, $key);
                } else {
                    echo($tab.self::TAB."[<em>".$key."</em>] => "
                            . "<strong>".self::specialToString($item)."</strong> "
                            . "(<span class='dumpf variable-type'>".gettype($item)."</span>)".self::EOL);
                }
            }
            echo($tab."}".self::EOL);
        } else {
            echo("EMPTY");
            echo("}".self::EOL);
        }
    }

    /**
     * Print all object property (recursively)
     *
     * @param object $obj Object (class) to print
     * @param int $level Level of nesting
     * @param string $key (optional) Jmeno promenne obsahujici zanoreny objekt
     */
    private static function dumpObject($obj, $level, $key = null) {
        $special_object_printers = array(
            "Dibi\DateTime" => function ($obj, $tab) {
                echo($tab.self::TAB."<em>date: </em><strong>".$obj->format("d. m. Y H:i:s")."</strong>".self::EOL);
                echo($tab.self::TAB."<em>timezone: </em><strong>".$obj->format("e P")."</strong>".self::EOL);
                echo($tab.self::TAB."<em>timestap: </em><strong>".$obj->format("U")."</strong>".self::EOL);
            }
        );

        $tab = '';
        for ($i = 1; $i < $level; $i++) {
            $tab .= self::TAB;
        }
        echo($tab);
        if (isset($key)) {
            echo('['.$key.'] => ');
        }

        $reflect = new \ReflectionClass($obj);
        $properties = $reflect->getProperties();

        // blue: #016FD9, red: #D42A2A, yellow: #f1c40f
        echo("object <strong style='color: #D42A2A;' class='dumpf object-name'>" . get_class($obj) . "</strong> {");

        if (array_key_exists(get_class($obj), $special_object_printers)) {
            echo(self::EOL);
            $special_object_printers[get_class($obj)]($obj, $tab);
            echo($tab."}".self::EOL);
        } elseif (count($properties) > 0) {
            echo(self::EOL);
            foreach ($properties as $item) {
                $var_name = $item->getName();
                $var = $reflect->getProperty($var_name);
                $var->setAccessible(true);
                $var_value = $var->getValue($obj);

                $var_scope = "";
                if ($var->isPrivate()) {
                    $var_scope = ":<span style='color: red;'>private</span>";
                } elseif ($var->isProtected()) {
                    $var_scope = ":<span style='color: orange;'>protected</span>";
                } elseif ($var->isStatic()) {
                    $var_scope = ":<span style='color: blue;'>static</span>";
                } else {
                    $var_scope = ":<span style='color: green;'>public</span>";
                }

                if (is_object($var_value)) {
                    self::dumpObject($var_value, $level+1, $var_name);
                } elseif (is_array($var_value)) {
                    self::dumpArray($var_value, $level+1, $var_name);
                } else {
                    echo($tab.self::TAB."[<em>".$item->getName().$var_scope."</em>] => "
                            . "<strong>".self::specialToString($var_value)."</strong> "
                            . "(".gettype($var_value).")".self::EOL);
                }
            }
            echo($tab . '}' . self::EOL);
        } else {
            echo(self::EOL);
            self::dumpObject($obj, $level+1);
            // $methods = get_class_methods($obj);
            // foreach ($methods as $method) {
            //     echo($tab . self::TAB . $method . '()' . self::EOL);
            // }
            echo($tab.'}' . self::EOL);
        }
    }

    /**
     * String representation of special variables (boolean, null or empty string)
     *
     * @param mixed $var
     *
     * @return string|int String if variable is type of boolean or $var
     */
    private static function specialToString($var) {
        $isBool = is_bool($var);
        if ($isBool && $var) {
            return self::TRUE_STRING;
        } elseif ($isBool) {
            return self::FALSE_STRING;
        } elseif (is_null($var) || !isset($var)) {
            return self::NULL_STRING;
        } elseif (is_string($var) && empty($var)) {
            return self::EMPTY_STRING;
        } else {
            return $var;
        }
    }
}
