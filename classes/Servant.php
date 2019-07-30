<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 27.07.19
 * Time: 15:19
 */

namespace classes;

use traits\TriggeredErrors;

class Servant
{

    use TriggeredErrors;

    /**
     * @var array
     */
    protected $_attributes = [];

    /**
     * @var array
     */
    protected $_values = [];

    /**
     * @var array
     */
    protected $_additions = [];

    /**
     * Servant constructor.
     * @param $arr
     */
    public function __construct($arr)
    {
        if (empty($arr))
            return;

        if (empty($this->_attributes)) {
            $this->_attributes = $arr;
        } else if (is_array($arr)) {
            $this->_attributes = array_merge($arr, $this->_attributes);
        }
    }

    /**
     * @param $arr
     */
    public function fill($arr)
    {
        foreach ($arr as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @param $name
     * @param $value
     */
    public function assign($name, $value)
    {
        $this->_additions[$name] = $value;
    }

    /**
     * magic
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        if (key_exists($name, $this->_attributes) && key_exists($name, $this->_values)) {
            return $this->_values[$name];
        } else if (key_exists($name, $this->_additions)) {
            return $this->_additions[$name];
        } else {
            $method = 'get' . ucfirst($name);
            if (method_exists($this, $method)) {
                return $this->$method();
            }
        }

        self::userNotice('Неопределенное свойство в __get(): ' . $name, debug_backtrace());

        return null;
    }

    /**
     * magic
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if (key_exists($name, $this->_attributes)) {
            $this->_values[$name] = $value;
        } else {
            $this->assign($name, $value);
        }
    }

    /**
     * magic
     * @param $name
     */
    public function __unset($name)
    {
        if (key_exists($name, $this->_attributes) && key_exists($name, $this->_values)) {
            unset($this->_values[$name]);
        } else if (key_exists($name, $this->_additions)) {
            unset($this->_additions[$name]);
        }
    }

    /**
     * magic
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return
            key_exists($name, $this->_attributes)
            && key_exists($name, $this->_values)
            || key_exists($name, $this->_additions);
    }
}