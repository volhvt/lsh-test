<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 28.07.19
 * Time: 23:43
 */

namespace traits;


trait TriggeredErrors
{
    static function userNotice($message,array $trace)
    {
        trigger_error(
            $message .
            ' в файле ' . $trace[0]['file'] .
            ' на строке ' . $trace[0]['line'],
            E_USER_NOTICE);
    }
}