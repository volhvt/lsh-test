<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 27.07.19
 * Time: 15:41
 */

namespace classes;


class DbExpression
{
    protected $_value;

    public function __construct($value)
    {
        $this->_value = $value;
    }

    public function getValue()
    {
        return $this->_value;
    }

    public function __toString()
    {
        return $this->getValue();
    }
}