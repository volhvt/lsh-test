<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 27.07.19
 * Time: 18:46
 */

/** @var \models\Operator[] $items */
?>

<h1>Операторы</h1>

<table class="table">
    <thead class="thead-dark">
    <tr>
        <th scope="col">#</th>
        <th scope="col">Номер</th>
        <th scope="col">Имя</th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>

    <?php
    $num = 0;
    foreach ($items as $item):?>
        <tr>
            <th scope="row"><?php echo ++$num; ?></th>
            <td><?php echo $item->number ?></td>
            <td><?php echo $item->name ?></td>
            <td></td>
        </tr>
    <?php endforeach; ?>

    </tbody>
    <tfoot>
    <tr>
        <th scope="col" colspan="4">

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

