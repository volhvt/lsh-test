<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 27.07.19
 * Time: 15:40
 */

namespace classes;


use PDO;
use PDOException;
use PDOStatement;

class Db extends Component
{
    /**
     * @var PDO $_conn
     */
    private $_conn;
    /**
     * @var string $_query
     */
    private $_query;
    /**
     * @var PDOStatement $_res
     */
    private $_res;

    function __construct($config)
    {
        parent::__construct($config);
        $dbConnectionString = $config['driver'] .
            ':host=' . $config['host'] .
            ';port=' . $config['port'] .
            ';dbname=' . $config['database'] .
            ';charset=' . $config['charset'];
        $this->_conn = new PDO($dbConnectionString, $config['username'], $config['password']);
    }

    function execute($query)
    {
        if (!$query) {
            throw new \Exception("Empty query");
        }

        $this->_query = $query;
        $this->_res = $this->_conn->prepare($query);
        if (!$this->_res->execute()) {
            throw new PDOException(implode("\n", $this->_res->errorInfo()));
        }

        return $this->_res->rowCount();
    }

    function select($query)
    {
        return $this->execute($query);
    }

    function selectResult($query)
    {
        $this->execute($query);

        return new DbResult($this->_res);
    }

    function getResult()
    {
        return new DbResult($this->_res);
    }

    function escapeString($value)
    {
        return $this->_conn->quote($value);
    }

    static public function raw($value)
    {
        return new DbExpression($value);
    }

    /**
     * @param null $name
     * @return string
     */
    public function lastInsertId($name = null)
    {
        return $this->_conn->lastInsertId($name);
    }

    public function begin()
    {

    }

    public function commit()
    {

    }

    public function rollback()
    {

    }
}