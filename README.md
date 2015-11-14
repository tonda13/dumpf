# dumpf
Pretty var_dump and break points functions

## Usage
`dumpf($var1, $var2, ...)` or `_d($var1, $var2, ...)`
or `_dx()` for call `exit()` in the end

`dumpf_pairs("name", $variable, ...)` or `_dp("name", $variable, ...)`

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

## TO-DO
* obalit některé metody do třídy, aby nešli volat přímo
