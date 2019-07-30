<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 27.07.19
 * Time: 14:58
 */
if (!function_exists('dd')) {
    function dd(...$args)
    {
        echo '<pre>';
        foreach ($args as $arg) {
            print_r($arg);
        }
        echo '</pre>';
        die();
    }
}
require('../classes/Application.php');
(classes\Application::getInstance('../config.php'))->run();