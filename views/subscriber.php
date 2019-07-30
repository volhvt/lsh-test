<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 27.07.19
 * Time: 20:11
 */

/** @var \models\Subscriber[] $items */
?>

<h1>Подписчики</h1>

<table class="table">
    <thead class="thead-dark">
    <tr>
        <th scope="col">#</th>
        <th scope="col">Ф.И.О</th>
        <th scope="col">Адрес</th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>

    <?php
    $num = 0;
    foreach ($items as $item):
        ?>
        <tr>
            <th scope="row"><?php echo ++$num; ?></th>
            <td><?php echo $item->fullName ?></td>
            <td><?php echo $item->address ?></td>
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