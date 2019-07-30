<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 28.07.19
 * Time: 0:45
 */

namespace models;


use classes\Model;

/**
 * This is the model class for table "check".
 *
 * The followings are the available columns in table 'check':
 * @property integer $id
 *
 * @property string $number
 * @property string $departure_date
 * @property string $tracking_number
 * @property string $status 'new'|'introduced'|'error'
 * @property string $file
 *
 * @property integer $subscription_id
 * @property integer $operator_id
 * @property integer $magazine_release_id
 *
 * @property Subscription $subscription
 * @property Subscriber $subscriber
 * @property Operator $operator
 * @property Magazine $magazine
 * @property MagazineRelease $magazineRelease
 */
class Check extends Model
{

    const STATUS_NEW = 'new';
    const STATUS_INTRODUCED = 'introduced';
    const STATUS_ERROR = 'error';

    protected static $_statusTitles = [
        self::STATUS_NEW        => 'Новый',
        self::STATUS_INTRODUCED => 'Внесенный',
        self::STATUS_ERROR      => 'Ошибочный'
    ];

    public static function statusTitles()
    {
        return self::$_statusTitles;
    }

    public function statusTitle()
    {
        return isset(self::$_statusTitles[$this->status])?self::$_statusTitles[$this->status]:'';
    }

    /**
     * @return Subscription|null
     */
    public function getSubscription()
    {
        return $this->belongTo(Subscription::class);
    }

    /**
     * @return Subscriber|null
     */
    public function getSubscriber()
    {
        return $this->belongToThrough(Subscriber::class, Subscription::class);
    }

    /**
     * @return Operator|null
     */
    public function getOperator()
    {
        return $this->belongTo(Operator::class);
    }

    /**
     * @return Magazine|null
     */
    public function getMagazine()
    {
        return $this->belongToThrough(Magazine::class, MagazineRelease::class);
    }

    /**
     * @return MagazineRelease|null
     */
    public function getMagazineRelease()
    {
        return $this->belongTo(MagazineRelease::class);
    }


    /**
     * @return bool
     */
    public function validate()
    {
        if (empty($this->name)) {
            $this->error('Укажите название');
        }

        if (empty($this->tid)) {
            $this->error('Укажите текстовый идентификатор');
        }

        return !$this->hasErrors();
    }

}