<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 27.07.19
 * Time: 20:07
 */

namespace controllers\backend;


use classes\Controller;
use models\Magazine;

class MagazineController extends Controller
{
    protected function _aIndex()
    {
        $this->render(
            'magazine',
            [
                'items' => Magazine::get()
            ]
        );
    }
}