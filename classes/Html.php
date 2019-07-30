<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 28.07.19
 * Time: 23:25
 */

namespace classes;


class Html
{
    static private $_PREFIX = 'lsh_html_element_';
    static private $_ID = 0;

    protected $_id;
    protected $_tag;
    protected $_params = [];
    protected $_content;

    protected function __construct($tag, $content = null, array $params = [])
    {
        $this->_id = self::$_PREFIX . (++self::$_ID);
        $this->_tag = $tag;
        $this->_params = $params;
        $this->_content = $content;
    }

    public function generate()
    {
        return self::tag(
            $this->_tag,
            $this->_content,
            array_merge(
                [
                    'id' => $this->_id
                ],
                is_array($this->_params) ? $this->_params : []
            )
        );
    }

    static function label($title, $for, array $params = [])
    {
        return new Html(
            'label',
            $title,
            array_merge(
                [
                    'for' => $for
                ],
                is_array($params) ? $params : []
            )
        );
    }

    static function input($type, array $params = [])
    {
        return new Html(
            'input',
            null,
            array_merge(
                [
                    'type' => $type
                ],
                is_array($params) ? $params : []
            )
        );
    }

    static function text(array $params = [])
    {
        return self::input('text', $params);
    }

    static function number(array $params = [])
    {
        return self::input('number', $params);
    }

    static function url(array $params = [])
    {
        return self::input('url', $params);
    }

    static function email(array $params = [])
    {
        return self::input('email', $params);
    }

    static function checkbox(array $params = [])
    {
        return self::input('checkbox', $params);
    }

    static function radio(array $params = [])
    {
        return self::input('radio', $params);
    }

    static function textarea($content = '', array $params = [])
    {
        return new Html(
            'textarea',
            $content,
            array_merge(
                [],
                is_array($params) ? $params : []
            )
        );
    }

    static function select(array $items = [], array $params = [], array $groups = [])
    {
        return new Html(
            'select',
            self::option($items),
            array_merge(
                [],
                is_array($params) ? $params : []
            )
        );
    }

    static function multiSelect(array $items = [], array $params = [], array $groups = [])
    {
        return self::select($items, $params, $groups);
    }

    static function option($content, array $params = [])
    {
        return new Html(
            'option',
            $content,
            array_merge(
                [],
                is_array($params) ? $params : []
            )
        );
    }

    static function options(array $items = [])
    {

    }

    static public function needCloseTag($tag)
    {
        return !in_array($tag, ['img', 'br', 'hr']);
    }

    static public function prepareTagContent($tag, $content, $level = 0)
    {
        if (is_array($content)) {
            $str = '';
            foreach ($content as $item) {
                $str .= self::prepareTagContent($tag, $item, $level + 1);
            }
            return $str;
        }
        if ($content instanceof Html !== false) {
            /** @var $content Html */
            return $content->generate();
        }
        return $content;
    }

    static public function needShieldParam($tag, $paramName)
    {
        return true;
    }

    static public function generateTagParams($tag, array $params = [], $prefix = '')
    {
        $str = '';
        foreach ($params as $name => $value) {
            $str .= ' ' . $prefix . $name;
            if ($value !== null && !is_array($value)) {
                $str .= '="' .
                    (
                    self::needShieldParam($tag, $name)
                        ? htmlspecialchars($value, ENT_COMPAT | ENT_HTML5)
                        : $value
                    ) . '"';
            } else if (is_array($value)) {
                $str .= '';
            }
        }
        return $str;
    }

    static public function tagOpen($tag, array $params = [])
    {
        return '<' . $tag . '' . self::generateTagParams($tag, $params) . '>';
    }

    static public function tagClose($tag)
    {
        return '</' . $tag . '>';
    }

    static public function tag($tag, $content = NULL, array $params = [], $forceNotClose = false)
    {
        return self::tagOpen($tag, $params) .
            self::prepareTagContent($tag, $content) .
            (
            (self::needCloseTag($tag) && !$forceNotClose)
                ? self::tagClose($tag)
                : ''
            );
    }

    public function __toString()
    {
        return $this->generate();
    }
}