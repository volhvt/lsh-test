<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 27.07.19
 * Time: 21:41
 */

/** @var \models\Subscription $item */
?>

<tr id="subscription_item_row_<?php echo sprintf("%d", $item->id); ?>"
    data-id="subscription_item_row_<?php echo sprintf("%d", $item->id); ?>">
    <th scope="row"><?php echo $item->id; ?></th>
    <td><?php echo $item->subscriber->fullName ?></td>
    <td><?php echo $item->subscriber->address ?></td>
    <td><?php echo $item->begin ?> / <?php echo $item->period ?></td>
    <td><?php echo $item->magazine->title ?></td>
    <td>
        <button><i class="fas fa-eye"></i></button>
        <button><i class="fas fa-link"></i></button>
    </td>
</tr>
