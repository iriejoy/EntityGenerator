<?php
namespace EntityGenerator\Helper;

class HelperFunctions
{
    public static $_type_string = ['char','varchar','blob','tinyblob','mediumblob','longblob','text','tinytext','mediumtext','longtext','enum'];

    public static $_type_integers = ['int','smallint','mediumint','integer','bigint'];

    public static $_type_floaters = ['decimal','double','float'];

    /* Capitalize the String name */
    public static function studlyCaps($string)
    {
        return implode('', array_map('ucfirst', explode('_', strtolower($string))));
    }

    /* Capitalize the String name */
    public static function camelCase($string)
    {
        $stringArray = explode('_', strtolower($string));
        $firstWord = (isset($stringArray)) ? $stringArray[0] : '';
        unset($stringArray[0]);
        return $firstWord.implode('', array_map('ucfirst', $stringArray));
    }

    /* Remove Type Metadata and allow only type (string, int, bool, float) */
    public static function typeAlias($str)
    {
        $str = str_replace('unsigned', '', $str);
        $str = preg_replace('~\(.*\)~', '', $str);
        $str = trim(preg_replace('/\s+/', ' ', $str));

        if (in_array($str, self::$_type_floaters)) {
            $str = str_replace($str, 'float', $str);
        } elseif (in_array($str, self::$_type_integers)) {
            $str = str_replace($str, 'int', $str);
        } else {
            $str = str_replace($str, 'string', $str);
        }

        return $str;
    }
}
