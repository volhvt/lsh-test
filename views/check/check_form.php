<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 27.07.19
 * Time: 21:08
 */
?>

<form action="/backend/subscription/create/" method="post" enctype="multipart/form-data">

    <div class="form-group">
        <label for="name" class="col-form-label">Подписчик:</label>
        <select class="form-control" name="check[subscriber_id]" id="check_subscriber_id"
                required="required">
            <option value="">...</option>
            <?php
            $subscribers = \models\Subscriber::get();
            if (is_array($subscribers)) {
                foreach ($subscribers as $subscriber) {
                    ?>
                    <option value="<?php echo $subscriber->id ?>"><?php echo $subscriber->fullName ?></option>
                    <?php
                }
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="name" class="col-form-label">Подписка:</label>
        <select class="form-control" name="check[subscription_id]" id="check_subscription_id"
                required="required">
            <option value="">...</option>
        </select>
    </div>

    <div class="form-group">
        <label for="message-text" class="col-form-label">Журнал:</label>
        <select class="form-control" name="check[magazine_id]" id="check_magazine_id" required="required">
            <option value="">...</option>
            <?php
            $magazines = \models\Magazine::get();
            if (is_array($magazines)) {
                foreach ($magazines as $magazine) {
                    ?>
                    <option
                            value="<?php echo $magazine->id ?>"><?php echo $magazine->title ?></option>
                    <?php
                }
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="message-text" class="col-form-label">Выпуск журнала:</label>
        <select class="form-control" name="check[magazine_release_id]" id="check_magazine_release_id" required="required">
            <option value="">...</option>
        </select>
    </div>

    <div class="form-group">
        <label for="message-text" class="col-form-label">Номер чека:</label>
        <input type="date" class="form-control" name="check[number]" id="check_number" required="required"/>
    </div>

    <div class="form-group">
        <label for="message-text" class="col-form-label">Дата отправки:</label>
        <input type="date" class="form-control" name="check[departure_date]" id="check_departure_date" required="required"/>
    </div>

    <div class="form-group">
        <label for="message-text" class="col-form-label">Номер трека:</label>
        <input type="date" class="form-control" name="check[tracking_number]" id="check_tracking_number" required="required"/>
    </div>

    <?php /* <div class="form-group">
        <label for="message-text" class="col-form-label">Статус:</label>
        <select class="form-control" name="check[status]" id="check_status" required="required">
            <?php
            foreach ( \models\Check::statusTitles() as $value=>$title) {
                ?>
                <option value="<?php echo $value ?>"><?php echo $title ?></option>
                <?php
            }
            ?>
        </select>
    </div> */ ?>


</form>
