<?php
/**
 * Created by PhpStorm.
 * User: brianetheridge
 * Date: 24/07/2016
 * Time: 14:24
 */

if (!function_exists('removeWhiteSpace')) {
    /**
     * Removes leading and trailing spaces and other white space.
     */
    function removeWhiteSpace($str) {
        $str = trim($str);
        $str = str_replace("\n", '', $str);
        $str = str_replace("\r", '', $str);
        return str_replace("\t", '', $str);
    }
}

if (!function_exists('trunc')) {
    /**
     * Removes leading and trailing spaces and other white space.
     */
    function trunc($str, $maxlen) {
        $str = trim($str);
        $maxStr = substr($str, 0, $maxlen);
        if ($str !== $maxStr) {
            return $maxStr . ' ...';
        }

        return $str;
    }
}
