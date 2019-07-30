<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 27.07.19
 * Time: 18:46
 */

/** @var \models\Check[] $items */
?>

<h1>Чеки</h1>

<table class="table">
    <thead class="thead-dark">
    <tr>
        <th scope="col">#</th>
        <th scope="col">Статус</th>
        <th scope="col">Номер / Номер трека</th>
        <th scope="col">Дата отправки</th>
        <th scope="col">Журнал</th>
        <th scope="col">Подписка / Подписчик</th>
        <th scope="col">Оператор</th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>

    <?php
    $num = 0;
    foreach ($items as $item):?>
        <tr>
            <th scope="row"><?php echo ++$num; ?></th>
            <td><?php echo $item->statusTitle() ?></td>
            <td><?php echo $item->number ?><br/><?php echo $item->tracking_number ?></td>
            <td><?php echo $item->departure_date ?></td>
            <td>
                <?php echo $item->magazine->title ?>
                <br/>
                [№<?php echo $item->magazineRelease->number ?> от <?php echo $item->magazineRelease->release?>]
            </td>
            <td>
                <?php echo $item->subscription->begin ?> / <?php echo $item->subscription->period ?> мес.
                <br/>
                <?php echo $item->subscriber->fullName ?>
                <br/>
                <?php echo $item->subscriber->address ?>
            </td>
            <td><?php echo $item->operator->name ?>(<?php echo $item->operator->number ?>)</td>
            <td>
                <button><i class="fas fa-download"></i></button>
            </td>
        </tr>
    <?php endforeach; ?>

    </tbody>
    <tfoot>
    <tr>
        <th scope="col" colspan="8">

            <nav aria-label="">
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

