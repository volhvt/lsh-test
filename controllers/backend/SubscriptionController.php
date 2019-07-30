<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 27.07.19
 * Time: 16:32
 */

namespace controllers\backend;

use classes\Application;
use classes\Controller;
use models\Subscription;

class SubscriptionController extends Controller
{
    protected $_status = 'success';
    protected $_message = 'Успешно добавлена';
    protected $_errors = [];

    /**
     *
     */
    protected function _aIndex()
    {
        $this->render(
            'subscription/subscriptions',
            [
                'items' => Subscription::get()
            ]
        );
    }

    /**
     *
     */
    protected function _aCreate()
    {
        $model = new Subscription();


        if (isset($_POST['subscription'])) {
            $status = 'success';
            $message = 'Успешно добавлена';
            $errors = [];

            $model->fill($_POST['subscription']);

            if (!($save = $model->save()) || $model->hasErrors()) {
                $errors = $model->getErrors();
                $status = 'error';
                $message = 'Не удалось добавить.';
            }

            if (Application::isAjaxRequest()) {
                $this->renderJSON(
                    array(
                        'status' => $status,
                        'message' => $message,
                        'errors' => $errors,
                        'content' => $this->renderPartial(
                            'subscription/subscription_item-row-table',
                            ['item' => $model],
                            true
                        )
                    )
                );
            } else if ($save) {
                header('Location: /backend/subscription/index/');
            }
        }

        $view = 'subscription/subscription_create';
        $context = array(
            'model' => $model,
        );
        if (Application::isAjaxRequest()) {
            $view = 'subscription/subscription_form';
            //Yii::app()->end();
            $this->renderJSON([
                'status' => 'success',
                'message' => '',
                'content' => $this->renderPartial($view, $context, true)
            ]);
        }

        $this->render($view, $context);
    }

    protected function _aUpdate()
    {

    }

    protected function _aDelete()
    {

    }
}