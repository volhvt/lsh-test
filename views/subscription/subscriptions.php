<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 27.07.19
 * Time: 17:55
 */

/** @var \models\Subscription[] $items */
?>
<h1>Подписки</h1>

<div class="btn-group" role="group" aria-label="subscriptions">
    <button type="button" class="btn btn-secondary" onclick="subscription_item_create();"><i class="fas fa-plus"></i>
        Добавить подписку
    </button>
    <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal"><i
                class="fas fa-search"></i> Поиск подписки
    </button>
</div>

<table class="table">
    <thead class="thead-dark">
    <tr>
        <th scope="col">#</th>
        <th scope="col">Ф.И.О</th>
        <th scope="col">Адрес</th>
        <th scope="col">Начало / Период(мес.)</th>
        <th scope="col">Журнал</th>
        <th scope="col"></th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody id="subscriptionItemsList">

    <?php
    foreach ($items as $item) {
        $this->renderPartial('subscription/subscription_item-row-table', ['item' => $item]);
    }
    ?>

    </tbody>
    <tfoot>
    <tr>
        <th scope="col" colspan="6">

            <nav aria-label="Подписки">
                <ul class="pagination justify-content-center">
                    <li class="page-item"><a class="page-link" href="#">Предыдущая</a></li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Следующая</a></li>
                </ul>
            </nav>

        </th>
    </tr>
    </tfoot>
</table>


<!-- Modal -->
<div class="modal fade" id="subscription_dialog" tabindex="-1" role="dialog" aria-labelledby="subscriptionDialog"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Редактирование/Добавление</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" onclick="subscription_submit()">Сохранить</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="subscription_dialog_error" tabindex="-1" role="dialog"
     aria-labelledby="subscriptionDialogError" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ошибка</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<script>

    if (typeof(entityAction) === 'undefined') {
        var entityAction = '';
    }

    if (typeof(entityWrapper) === 'undefined') {
        var entityWrapper = '';
    }

    if (typeof(dlgs) === 'undefined') {
        var dlgs = {};
    }

    $.extend(dlgs, {
        dlgSubscription: '#subscription_dialog',
        dlgSubscriptionError: '#subscription_dialog_error',
    });

    var crud_subscription_params = {
        wrapperId: 'subscriptionItemsList',
        rowId: 'subscription_item_row__id_',
        dialogId: 'subscription_dialog',
        dialogName: 'dlgSubscription',
        dialogOptions: {}
    };

    function subscription_submit() {
        $('#' + crud_subscription_params['dialogId'] + ' .modal-body').find('form').submit();
    }

    function subscription_item_create() {
        var url = '/backend/subscription/create/';
        _entity_create(url, crud_subscription_params);
    }

    function subscription_item_edit(id) {
        var url = '/backend/subscription/update/id/_id_'.replace('_id_', id);
        crud_subscription_params['rowId'] = crud_subscription_params['rowId'].replace('_id_', id);
        _entity_edit(url, crud_subscription_params);
    }

    function subscription_item_delete(id) {
        var url = '/backend/subscription/delete/id/_id_'.replace('_id_', id);
        crud_subscription_params['rowId'] = crud_subscription_params['rowId'].replace('_id_', id);
        _entity_delete(url, crud_subscription_params);
    }

    $(document).ready(function () {
        bindDialogForm(crud_subscription_params['dialogName']);
    });

</script>