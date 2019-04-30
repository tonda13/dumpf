# dumpf
Pretty dump variables library. Easy to use with global shortcut function aliases.

## Usage
```php
Morgo\Dumpf::dump($x, $y);

Morgo\DumpfPair::dump('label_for_x', $x)
```

## Examples
```php
$var1 = "Some string";
$var2 = 2 * 5;
_d($var1, $var2);
_dp("String", $var1, "Number", $var2);
```
### Output
```php
string (11): Some string
integer: 10
String = string (11): Some string
Number = integer: 10
```


## How to call dev package through composer locally
Add to composer.json following piece of config:
```
"repositories": {
    "dumpf": {
        "type": "path",
        "url": "../dumpf",
        "options": {
            "symlink": true
        }
    }
},
```
Then you can normally require
`composer require morgo/dumpf @dev`
