<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 27.07.19
 * Time: 15:28
 */

namespace classes;

use JsonSerializable;
use PDOException;
use traits\TriggeredErrors;

abstract class Model extends Servant implements JsonSerializable
{
    use TriggeredErrors;

    const SCENARIO_SAVE = 'save';
    const SCENARIO_UPDATE = 'update';

    const HAS_ONE = 'one';
    const HAS_MANY = 'many';
    const BELONG_TO = 'belong';

    /**
     * @var string
     */
    protected $_scenario;

    /**
     * @var string
     */
    protected $_defaultOrder;

    /**
     * @var Db $_connection
     */
    protected $_connection;
    /**
     * @var string $_table
     */
    protected $_table;
    /**
     * @var string $_primaryKey
     */
    protected $_primaryKey = 'id';

    /**
     * @var string $_keyType
     */
    protected $_keyType = 'int';

    /**
     * @var bool $_incrementing
     */
    public $_incrementing = true;

    /**
     * @var array $_fields
     * (
     *  [column_name] => name
     *  [data_type] => character varying
     *  [character_maximum_length] => 150
     *  [table_name] => settings
     *  [ordinal_position] => 2
     *  [is_nullable] => NO
     * )
     */
    protected $_fields;

    /**
     * @var array $_relations
     */
    protected $_relations = [];

    /**
     * @var string $_name
     */
    protected $_name;

    /**
     * @var array $_errors
     */
    protected $_errors = [];


    /**
     * Model constructor.
     * @param array $values
     */
    public function __construct($values = [])
    {
        if (empty($this->_table)) {
            $this->_table = self::getPrepareName(get_called_class());
        }

        if (empty($this->_table)) {
            $this->_name = self::getPrepareName(get_called_class());
        }

        $query = '
          SELECT column_name, data_type, character_maximum_length, table_name,ordinal_position, is_nullable 
          FROM information_schema.COLUMNS WHERE table_name LIKE \'' . $this->_table . '\'
          ORDER BY ordinal_position
        ';

        $fields = [];

        $connection = $this->getConnection();
        $res = $connection->selectResult($query);
        $count = $res->count();
        $i = 0;

        if ($count > 0) {
            while ($row = $res->fetch()) {
                $fields[$row['column_name']] = null;
                $this->_fields[$row['column_name']] = $row;
                $i++;
            }
        }

        parent::__construct($fields);
        $this->fill($values);
    }

    /**
     * @param string $name
     * @return array|bool|mixed|null
     */
    public function __get($name)
    {
        if (key_exists($name, $this->_attributes)) {
            return key_exists($name, $this->_values) ? $this->_values[$name] : null;
        } else if (key_exists($name, $this->_additions)) {
            return $this->_additions[$name];
        } else {
            $method = 'get' . ucfirst($name);
            if (method_exists($this, $method)) {
                return $this->$method();
            } else {

                if (key_exists($name, $this->_relations)) {
                    return $this->getRelationEntities($name);
                }
            }
        }
        self::userNotice('Неопределенное свойство в __get(): ' . $name, debug_backtrace());
        return null;
    }

    /**
     * @param string $className
     * @param string $glue
     * @return string
     */
    public static function getPrepareName($className, $glue = '_')
    {
        $names = explode('\\', $className);
        $name = array_pop($names);

        return StringHelper::GetFileNameByClass(strtr($name, ['Model' => '']), $glue);
    }

    /**
     * @param array $values
     * @return mixed
     */
    static protected function _model($values = [])
    {
        $class = get_called_class();
        return new $class($values);
    }

    /**
     * @param mixed $ids
     * @return Model[]|null
     * @throws \Exception
     */
    static public function get($ids = NULL)
    {
        $instance = new static;
        return self::getBy($instance->getPrimaryKey(), $ids);
    }

    /**
     * @param string $key
     * @param mixed $values
     * @return Model[]|null
     * @throws \Exception
     */
    static public function getBy($key, $values = NULL)
    {
        if (!empty($values) && !is_array($values)) {
            $values = [$values];
        }
        $instance = new static;
        $query = 'SELECT * from `' . $instance->getTable() .'`';
        if (!empty($values)) {
            $query .= ' WHERE ' . $key . ' IN ( \'' . implode('\', \'', $values) . '\' )';
        }
        if (!empty($instance->_defaultOrder)) {
            $query .= ' ORDER BY ' . $instance->_defaultOrder;
        }
        $connection = $instance->getConnection();
        return self::populate($connection->SelectResult($query));
    }

    /**
     * @param array $key_values
     * @return Model[]|null
     * @throws \Exception
     */
    static public function getBys(array $key_values)
    {
        $instance = new static;
        if (empty($key_values)) {
            return null;
        }

        $str = '';
        foreach ($key_values as $key => $value) {
            if (!empty($str)) {
                $str .= ' AND ';
            }
            $str .= $key . ' = \'' . $value . '\'';
        }

        $query = 'SELECT * from `' . $instance->getTable() . '` WHERE ' . $str;
        if (!empty($instance->_defaultOrder)) {
            $query .= ' ORDER BY ' . $instance->_defaultOrder;
        }

        $connection = $instance->getConnection();
        return self::populate($connection->SelectResult($query));
    }

    /**
     * @param string $key
     * @param bool $withDoubles
     * @return array
     * @throws \Exception
     */
    static public function getAssocByKey($key, $withDoubles = false)
    {
        $ar = self::get();
        $accos = [];
        foreach ($ar as $item) {
            if ($withDoubles) {
                if (empty($accos[$item->{$key}])) {
                    $accos[$item->{$key}] = [];
                }
                $accos[$item->{$key}][] = $item;
            } else {
                $accos[$item->{$key}] = $item;
            }
        }
        return $accos;
    }

    static public function getList($titleField, $valueField, $glue = ' ')
    {
        if (is_array($valueField)) {

        }
    }

    /**
     * @param $id
     * @return Model|null
     * @throws \Exception
     */
    static public function find($id)
    {
        $instance = new static;
        return self::findBy($instance->getPrimaryKey(), $id);
    }

    /**
     * @param $key
     * @param $value
     * @return Model|null
     * @throws \Exception
     */
    static public function findBy($key, $value)
    {
        $instance = new static;
        $query = 'SELECT * from `' . $instance->getTable() . '` WHERE ' . $key . ' = \'' . $value . '\' LIMIT 1';
        $connection = $instance->getConnection();
        $res = $connection->selectResult($query);
        return self::populate($res->fetch());
    }

    /**
     * @param $values
     * @return Model
     * @throws \Exception
     */
    static public function store($values)
    {
        $instance = new static($values);
        $instance->save();
        return $instance;
    }

    /**
     * @param $arr
     * @param string $className
     * @return Model[]|Model|null
     * @throws \Exception
     */
    public static function populate($arr,$className=null)
    {
        if (is_array($arr) && !empty($arr)) {
            $instance = $className?new $className:new static;
            $instance->fill($arr);
            return $instance;
        } else if (($arr instanceof DbResult !== false)) {
            $count = $arr->count();
            $i = 0;
            $array = [];
            if ($count > 0) {
                while ($i < $count) {
                    $row = $arr->fetch();
                    $array[] = self::populate($row,$className);
                    $i++;
                }
            }
            return $array;
        }
        return null;
    }

    /**
     * @return array
     */
    public function clearErrors()
    {
        $er = $this->_errors;
        $this->_errors = [];
        return $er;
    }

    /**
     * @param $message
     */
    public function error($message)
    {
        $this->_errors[] = $message;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return !empty($this->_errors);
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function save()
    {
        $this->_scenario = isset($this->{$this->getPrimaryKey()})
            ? self::SCENARIO_UPDATE
            : self::SCENARIO_SAVE;

        if (!$this->validate()) {
            return 0;
        }

        $_ar = $this->_values;

        if (key_exists($this->getPrimaryKey(), $_ar)) {
            unset($_ar[$this->getPrimaryKey()]);
        }

        if (isset($this->{$this->getPrimaryKey()})) {

            $str = '';
            foreach ($_ar as $key => $value) {
                if ($str !== '') {
                    $str .= ', ';
                }
                $str .= $key . ' = ' . $this->getPrepareColumnValue($value, $key);
            }
            $query = 'UPDATE ' . $this->getTable() . ' SET ' . $str .
                ' WHERE ' . $this->getPrimaryKey() . ' = ' . $this->{$this->getPrimaryKey()} . ';';
        } else {
            $str = '';
            foreach ($_ar as $key => $value) {
                if ($str !== '') {
                    $str .= ', ';
                }
                $str .= $this->getPrepareColumnValue($value, $key);
            }
            $query = 'INSERT INTO ' . $this->getTable() . ' (' . implode(', ', array_keys($_ar)) . ')' .
                ' VALUES ( ' . $str . ');';
        }


        $connection = $this->getConnection();
        try {
            $connection->selectResult($query);
            $this->{$this->getPrimaryKey()} = $connection->lastInsertId($this->getPrimaryKey());
        } catch (PDOException $e) {
            $this->error($e->getMessage());
        }

        return isset($this->{$this->getPrimaryKey()}) ? $this->{$this->getPrimaryKey()} : 0;
    }

    /**
     * @return bool
     */
    public function validate()
    {
        return true;
    }

    /**
     * @param mixed $value
     * @param $column
     * @return string
     * @throws \Exception
     */
    public function getPrepareColumnValue($value, $column)
    {
        if (is_null($value)) {
            return 'NULL';
        } else if (($value instanceof DbExpression !== false)) {
            $value = $value->getValue();
        } else if (
            isset($this->_fields[$column])
            && strpos(strtolower($this->_fields[$column]['data_type']), 'int') !== false
        ) {
            $value = sprintf('%d', $value);
        } else if (
            isset($this->_fields[$column])
            && strpos(strtolower($this->_fields[$column]['data_type']), 'bool') !== false
        ) {

        } else if (
            isset($this->_fields[$column])
            && strpos(strtolower($this->_fields[$column]['data_type']), 'date') !== false
        ) {
            $value = $this->getConnection()->escapeString($value);
        } else {
            $value = $this->getConnection()->escapeString($value);
        }
        return $value;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * @return Db|mixed
     * @throws \Exception
     */
    public function getConnection()
    {
        return !empty($this->_connection)
            ? $this->_connection
            : Application::getComponent('db');
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->_table;
    }

    /**
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->_primaryKey;
    }

    /**
     * @return array
     */
    public function getArray()
    {
        $addition = [];
        foreach ($this->_additions as $name => $value) {
            if (is_object($value) && is_a($value, 'Model'))
                $value = $value->getArray();

            $addition[$name] = $value;
        }
        return $this->_values + $addition;
    }

    /**
     * @return array|mixed
     */
    function jsonSerialize()
    {
        return $this->getArray();
    }

    /**
     * @param string $key
     * @return bool
     */
    function attributeExists($key)
    {
        return key_exists($key, $this->_attributes);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws \Exception
     */
    static public function __callStatic($name, $arguments = [])
    {
        $instance = new static;
        $ar = ['getBy', 'findBy'];
        foreach ($ar as $sMethod) {
            if (strpos($name, $sMethod) === 0) {
                $key = lcfirst(strtr($name, [$sMethod => '']));
                if ($instance->attributeExists($key)) {
                    return self::$sMethod($key, $arguments[0]);
                }
            }
        }
    }

    /**
     * @param string $className
     * @param string $foreignKey
     * @param string $foreignKey2
     * @return mixed
     * @throws \Exception
     */
    public function belongTo($className, $foreignKey = null, $foreignKey2 = 'id')
    {
        $name = self::getPrepareName($className);
        if (empty($foreignKey))
            $foreignKey = $name . '_id';

        if (!key_exists($name, $this->_relations))
            $this->_relations[$name] = $className::find($this->{$foreignKey});

        return $this->_relations[$name];
    }

    /**
     * @param string $className
     * @param string $classNameThrough
     * @param string $foreignKey
     * @param string $foreignKeyThrough
     * @param string $foreignKey2
     * @return mixed
     * @throws \Exception
     */
    public function belongToThrough($className, $classNameThrough, $foreignKey = null, $foreignKeyThrough = null, $foreignKey1 = 'id', $foreignKey2 = 'id')
    {
        $name = self::getPrepareName($className);
        if (empty($foreignKey))
            $foreignKey = $name . '_id';

        if (!key_exists($name, $this->_relations)) {
            $nameThrough = self::getPrepareName($classNameThrough);
            if (empty($foreignKeyThrough))
                $foreignKeyThrough = $nameThrough . '_id';

            /** @var Model $instance */
            $instance = new $className();
            /** @var Model $instanceThrough */
            $instanceThrough = new $classNameThrough();

            $query = 'SELECT * from `' . $instance->getTable() . '` as destination' .
                ' LEFT JOIN `' . $instanceThrough->getTable() . '` as through' .
                ' ON through.' . $foreignKey . ' = destination.' . $foreignKey2 .
                ' WHERE through.' . $foreignKey1 . ' = '.$this->{$foreignKeyThrough}.' LIMIT 1';

            $connection = $instance->getConnection();
            $res = $connection->selectResult($query);
            $this->_relations[$name] = self::populate($res->fetch(),$className);

        }

        return $this->_relations[$name];
    }

    /**
     * @param string $className
     * @param string $foreignKey
     * @param string $foreignKey2
     * @return mixed
     * @throws \Exception
     */
    public function hasMany($className, $foreignKey = null, $foreignKey2 = 'id')
    {
        $name = self::getPrepareName($className);
        $names = $name.'s';

        if (empty($foreignKey))
            $foreignKey = $name . '_id';

        if (!key_exists($names, $this->_relations))
            $this->_relations[$names] = $className::getBy($foreignKey,$this->{$foreignKey});

        return $this->_relations[$names];
    }


    public function validRelations()
    {

    }

    public function relations()
    {
        return $this->_relations;
    }

    public function getRelation($relationName)
    {
        return key_exists($relationName, $this->_relations) ? $this->_relations[$relationName] : null;
    }

    public function manyRelationEntities($relationName)
    {
        $entities = [];
        $relationParams = $this->getRelation($relationName);
        if ($relationParams) {
            $entities = $relationParams[1]::getBy($relationParams[2], [$this->{$this->_primaryKey}]);
            if ($entities)
                $this->assign($relationName, $entities);

        }
        return $entities;
    }

    public function oneRelationEntity($relationName)
    {
        $entity = null;
        $relationParams = $this->getRelation($relationName);
        if ($relationParams) {
            $entity = $relationParams[1]::findBy($this->{$this->_primaryKey}, $relationParams[2]);
            if ($entity)
                $this->assign($relationName, $entity);

        }
        return $entity;
    }

    public function belongRelationEntity($relationName)
    {
        return $this->oneRelationEntity($relationName);
    }

    public function getRelationEntities($relationName)
    {
        $relationParams = $this->getRelation($relationName);
        if ($relationParams) {
            switch ($relationParams[0]) {
                case self::HAS_ONE:
                    return $this->oneRelationEntity($relationName);
                case self::HAS_MANY:
                    return $this->manyRelationEntities($relationName);
                    break;
                case self::BELONG_TO:
                    return $this->belongRelationEntity($relationName);
                    break;
            }
        }
        return false;
    }
}