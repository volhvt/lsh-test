<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 27.07.19
 * Time: 20:13
 */

namespace controllers\backend;


use classes\Controller;
use models\Subscriber;

class SubscriberController extends Controller
{
    protected function _aIndex()
    {
        $this->render(
            'subscriber',
            [
                'items' => Subscriber::get()
            ]
        );
    }
}