<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 27.07.19
 * Time: 17:30
 */

namespace classes;


class Component
{
    protected $_config;

    function __construct($config = null)
    {
        $this->_config = $config;
    }
}