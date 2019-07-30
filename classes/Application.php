<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 27.07.19
 * Time: 15:21
 */

namespace classes;

define('BASE', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);

include(BASE . 'traits' . DIRECTORY_SEPARATOR . 'TriggeredErrors.php');

use traits\TriggeredErrors;
use Exception;

class Application
{
    use TriggeredErrors;

    protected $_config = [];

    protected static $_instance = null;

    protected $_components = [];

    public static function getInstance($configFile = NULL)
    {
        if (!isset(self::$_instance))
            self::$_instance = new self($configFile);

        return self::$_instance;
    }

    protected function __construct($configFile)
    {
        $this->_config = include($configFile);
        spl_autoload_register([$this, '_autoload']);
    }

    /**
     * @param $name
     * @throws Exception
     */
    private function _autoload($name)
    {
        $classFile = BASE . strtr($name, ['\\' => DIRECTORY_SEPARATOR]) . '.php';
        if (!is_file($classFile))
            throw new Exception('for class ' . $name . ', can not found file ' . $classFile);
        require_once($classFile);
        if (!class_exists($name, false))
            throw new Exception('class ' . $name . ' not found in ' . $classFile);
    }

    /**
     * @param $part
     * @param $controller
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    public function loadController($controller, $part, $params = [])
    {
        $controllerClass = 'controllers\\' . $part . '\\' . ucfirst($controller) . 'Controller';
        $controllerClassFile = BASE . strtr($controllerClass, ['\\' => DIRECTORY_SEPARATOR]) . '.php';
        if (!is_file($controllerClassFile))
            throw new Exception('for class ' . $controllerClass . ', can not found file ' . $controllerClassFile);
        require_once($controllerClassFile);
        if (!class_exists($controllerClass, false))
            throw new Exception('class ' . $controllerClass . ' not found in ' . $controllerClassFile);
        return new $controllerClass($params);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function run()
    {
        $controller = strtolower('subscription');
        $part = strtolower('backend');
        $action = 'index';

        $parts = explode('/',
            strpos($_SERVER['REQUEST_URI'], '/') === 0
                ? substr($_SERVER['REQUEST_URI'], 1)
                : $_SERVER['REQUEST_URI']
        );

        if (!empty($parts[0])) {
            $part = $parts[0];
            array_shift($parts);

            if (isset($parts[0])) {
                $controller = $parts[0];
                array_shift($parts);
            }

            if (isset($parts[0])) {
                $action = $parts[0];
                array_shift($parts);
            }
        }

        return $this->loadController($controller, $part)->action($action, $parts);
    }

    /**
     * @param string $alias
     */
    static public function getPathOfAlias($alias)
    {

    }

    /**
     * @param $name
     * @return mixed
     * @throws Exception
     */
    public function instanceComponent($name)
    {
        if (empty($this->_components[$name])) {
            if (empty($this->_config[$name]['class']))
                throw new Exception($name . ' - component not fount in config');
            $class = $this->_config[$name]['class'];
            $this->_components[$name] = new $class($this->_config[$name]);
        }
        return $this->_components[$name];
    }

    /**
     * @param $name
     * @return mixed
     * @throws Exception
     */
    static public function getComponent($name)
    {
        return self::getInstance()->instanceComponent($name);
    }

    static public function isAjaxRequest()
    {
        //X-Requested-With: XMLHttpRequest
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == strtolower('XMLHttpRequest');
    }

    /**
     * magic
     * @param $name
     * @param $arguments
     * @return mixed
     */
    static public function __callStatic($name, $arguments = [])
    {
        try {
            $instance = self::getInstance()->instanceComponent($name);
            return $instance;
        } catch (Exception $e) {
            self::userNotice($e->getMessage(), debug_backtrace());
        }
        self::userNotice('Неопределенное свойство в __callStatic(): ' . $name, debug_backtrace());
        return null;
    }

}