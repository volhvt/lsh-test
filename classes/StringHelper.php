<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 28.07.19
 * Time: 18:58
 */

namespace classes;


class StringHelper
{
    /**
     * возвращает имя файла по названию класса
     * @param string $class
     * @param string $glue
     * @return string
     */
    static public function GetFileNameByClass($class, $glue = '_')
    {
        $pattern = '/([A-Z][^A-Z]+)/';
        if (preg_match_all($pattern, $class, $matches)) {
            $class = implode($glue, $matches[1]);
        }
        return mb_strtolower($class);
    }

}