<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 27.07.19
 * Time: 16:22
 */

namespace classes;


use Exception;

class Controller
{
    protected $_layout = 'layout';

    protected $_requestParams = [];

    /**
     * @param $name
     * @param array $requestParams
     * @throws Exception
     */
    public function action($name, $requestParams = [])
    {
        $method = '_a' . ucfirst($name);
        if (method_exists($this, $method)) {
            $this->_requestParams = $requestParams;
            $this->{$method}();
        } else {
            throw new Exception('action ' . $name . ' not exist');
        }

    }

    public function render($view, $data)
    {
        $content = $this->renderPartial($view, $data, true);
        include BASE . 'views' . DIRECTORY_SEPARATOR . $this->_layout . '.php';
    }

    public function renderPartial($view, $data, $___return = false)
    {
        if ($___return) {
            ob_start();
        }

        extract($data);
        include BASE . 'views' . DIRECTORY_SEPARATOR . $view . '.php';

        if ($___return) {
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }
    }

    public function renderJSON($data)
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit(0);
    }
}