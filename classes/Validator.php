<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 28.07.19
 * Time: 23:20
 */

namespace classes;


class Validator
{
    protected $_key;

    protected $_rules = [];

    protected $_errors = [];

    public function __construct($key, $rulesStr)
    {
        $this->_key = $key;
        $this->_rules = explode('|', $rulesStr);
    }

    public function valid($value)
    {
        $this->clearErrors();
        foreach ($this->_rules as $rule) {
            $method = 'valid' . ucfirst($rule);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
        return $this->hasErrors();
    }

    public function clearErrors()
    {
        $this->_errors = [];
    }

    public function error($message)
    {
        $this->_errors[] = $message;
    }

    public function errors($arr)
    {
        $this->_errors = array_merge($this->_errors, $arr);
    }

    public function hasErrors()
    {
        return !empty($this->_errors);
    }

    public function getErrors()
    {
        return $this->_errors;
    }


    /** ============================================ */

    /**
     * @param $value
     * @return bool
     */
    public function validRequired($value)
    {
        if (is_null($value) || $value === '') {
            $this->error($this->_key . '.required');
            return false;
        }
        return true;
    }
}