<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 27.07.19
 * Time: 15:41
 */

namespace classes;


use PDO;
use PDOStatement;

class DbResult
{
    /**
     * @var PDOStatement $_res
     */
    protected $_res;

    public function __construct($res)
    {
        $this->_res = $res;
    }

    function count()
    {
        if ($this->_res) {
            return $this->_res->rowCount();
        }
        return 0;
    }

    function fetch()
    {
        return $this->_res->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
    }

}