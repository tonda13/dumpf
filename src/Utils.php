<?php
namespace Morgo;

/**
 * Utils used by PrettyDump classes
 */
class Utils
{
    /**
     * Set encoding of Content-Type header to UTF-8
     * @return void
     */
    public static function setUtf8EncodingHeader() : void {
        if (!headers_sent()) {
            header('Content-Type: text/html; charset=utf-8');
        }
    }
}
