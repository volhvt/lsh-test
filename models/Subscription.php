<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 27.07.19
 * Time: 17:04
 */

namespace models;

use classes\Model;

/**
 * This is the model class for table "subscription".
 *
 * The followings are the available columns in table 'subscription':
 * @property integer $id
 * @property integer $subscriber_id
 * @property integer $magazine_id
 * @property string $begin
 * @property integer $period
 *
 * @property Subscriber $subscriber
 * @property Magazine $magazine
 * @property Check[] $checks
 */
class Subscription extends Model
{
    const MIN_PERIOD = 1;
    const MAX_PERIOD = 12;

    /**
     * @var string
     */
    protected $_defaultOrder = 'subscriber_id asc';

    /**
     * @return Subscriber|null
     */
    public function getSubscriber()
    {
        return $this->belongTo(Subscriber::class);
    }

    /**
     * @return Magazine|null
     */
    public function getMagazine()
    {
        return $this->belongTo(Magazine::class);
    }

    /**
     * @return Check[]|null
     */
    public function getChecks()
    {
        return $this->hasMany(Check::class);
    }

    /**
     * @return bool
     */
    public function validate()
    {
        if (empty($this->subscriber_id)) {
            $this->error('Укажите подписчика');
        }

        if (empty($this->magazine_id)) {
            $this->error('Укажите журнал');
        }

        if ($this->_scenario == self::SCENARIO_SAVE && !empty($this->subscriber_id) && !empty($this->magazine_id)) {
            $items = self::getBys(
                ['magazine_id' => $this->magazine_id, 'subscriber_id' => $this->subscriber_id]
            );
            if (!empty($items)) {
                $this->error(
                    'Пользователь ' . $this->subscriber->fullName . ' уже подписан на журнал ' . $this->magazine->title
                );
                return false;
            }
        }

        if (empty($this->begin)) {
            $this->error('Укажите дату начала подписки');
        }

        if (empty($this->period)) {
            $this->error('Укажите период подписки');
        }

        return !$this->hasErrors();
    }
}