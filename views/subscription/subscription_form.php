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
        <select class="form-control" name="subscription[subscriber_id]" id="subscription_subscriber_id"
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
        <label for="message-text" class="col-form-label">Журнал:</label>
        <select class="form-control" name="subscription[magazine_id]" id="subscription_magazine_id" required="required">
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
        <label for="message-text" class="col-form-label">Дата начала подписки:</label>
        <input type="date" class="form-control" name="subscription[begin]" id="subscription_begin" required="required"/>
    </div>

    <div class="form-group">
        <label for="message-text" class="col-form-label">Период(мес.):</label>
        <select class="form-control" name="subscription[period]" id="subscription_period" required="required">
            <option value="">...</option>
            <?php
            for ($i = \models\Subscription::MIN_PERIOD; $i <= \models\Subscription::MAX_PERIOD; $i++) {
                ?>
                <option value="<?php echo $i ?>"><?php echo $i ?></option>
                <?php
            }
            ?>
        </select>
    </div>


</form>
