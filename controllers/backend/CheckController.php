<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 28.07.19
 * Time: 18:33
 */

namespace controllers\backend;


use classes\Controller;
use models\Check;

class CheckController extends Controller
{

    protected function _aIndex()
    {
        $this->render(
            'check/checks',
            [
                'items' => Check::get()
            ]
        );
    }

    protected function _aScan()
    {
        $model = new Check();

        try {
            $model->file = ScanService::scan();
            $recognition = ScanRecognitionService::recognition($model->file);
            $model->fill($recognition);
            $model->save();
        } catch (\Exception $e) {

        }
    }

    protected function _aStatus()
    {
        $model = Check::find($_REQUEST['id']);
        $this->renderJSON($model);
    }
}