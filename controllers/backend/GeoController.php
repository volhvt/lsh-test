<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 27.07.19
 * Time: 18:45
 */

namespace controllers\backend;

use classes\Controller;
use models\Country;

class GeoController extends Controller
{
    protected function _aIndex()
    {
        $this->render(
            'geo',
            [
                'items' => Country::get()
            ]
        );
    }
}