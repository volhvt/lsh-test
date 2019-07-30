<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 28.07.19
 * Time: 23:24
 */

namespace classes;


class EntityForm
{
    static private $_PREFIX = 'lsh_form_';
    static private $_ID = 0;

    protected $_id;

    protected $_model;

    protected $_action;

    protected $_options;

    public function __construct($action, Model $model = null, array $options = [])
    {
        $this->_id = self::$_PREFIX . (++self::$_ID);
        $this->_action = $action;
        $this->_model = $model;
        $this->_options = $options;
    }

    public static function generateName($name, $model)
    {
        /** @var $model Model */
        if ($model) {
            $arrStr = '';
            /*$matches = [];
            if( preg_match_all('/\[([\w|\d|_|-]+)\]/',$name,$matches) ) {

            }*/
            if (($pos = strpos($name, '['))) {
                $arrStr = substr($name, $pos);
                $name = substr($name, 0, $pos);
            }
            return $model::getPrepareName('_') . '[' . $name . ']' . $arrStr;
        }
        return $name;
    }

    public function fieldLabel($for, $title, array $params = [])
    {
        return Html::label(
            $title,
            $for,
            array_merge(
                [],
                is_array($params) ? $params : []
            )
        );
    }

    public function fieldText($name, $value = null, array $params = [])
    {
        return Html::text(
            array_merge(
                [
                    'name' => self::generateName($name, $this->_model),
                    'value' => $value
                ],
                is_array($params) ? $params : []
            )
        );
    }

    public function fieldNumber($name, $value = null, array $params = [])
    {
        return Html::number(
            array_merge(
                [
                    'name' => self::generateName($name, $this->_model),
                    'value' => $value
                ],
                is_array($params) ? $params : []
            )
        );
    }

    public function fieldUrl($name, $value = null, array $params = [])
    {
        return Html::url(
            array_merge(
                [
                    'name' => self::generateName($name, $this->_model),
                    'value' => $value
                ],
                is_array($params) ? $params : []
            )
        );
    }

    public function fieldEmail($name, $value = null, array $params = [])
    {
        return Html::email(
            array_merge(
                [
                    'name' => self::generateName($name, $this->_model),
                    'value' => $value
                ],
                is_array($params) ? $params : []
            )
        );
    }

    public function fieldCheckbox($name, $value = null, $checked = null, array $params = [])
    {
        return Html::checkbox(
            array_merge(
                [
                    'name' => self::generateName($name, $this->_model),
                    'value' => $value
                ],
                is_array($params) ? $params : []
            )
        );
    }

    public function fieldRadio($name, $value = null, $checked = null, array $params = [])
    {
        return Html::radio(
            array_merge(
                [
                    'name' => self::generateName($name, $this->_model),
                    'value' => $value
                ],
                is_array($params) ? $params : []
            )
        );
    }

    public function fieldTextarea($name, $value = null, array $params = [])
    {
        return Html::textarea(
            $value,
            array_merge(
                [
                    'name' => self::generateName($name, $this->_model),
                ],
                is_array($params) ? $params : []
            )
        );
    }

    public function fieldSelect($name, $selected = null, array $items = [], array $params = [], array $groups = [])
    {
        return Html::select(
            $items,
            array_merge(
                [
                    'name' => self::generateName($name, $this->_model),
                ],
                is_array($params) ? $params : []
            )
        );
    }

    public function fieldMultiSelect($name, array $selected = [], array $items = [], array $params = [], array $groups = [])
    {
        return Html::multiSelect(
            $items,
            array_merge(
                [],
                is_array($params) ? $params : []
            )
        );
    }

    static public $_form;

    static public function open($action, Model $model = null, array $options = [])
    {
        self::$_form = new EntityForm($action, $model, $options);

        return self::$_form;
    }

    static public function close()
    {
        if (self::$_form) {
            self::$_form = null;
        }
    }

}