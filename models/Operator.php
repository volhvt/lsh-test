<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 29.07.19
 * Time: 20:58
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
 * @property string $name
 * @property string $status 'offline'|'ready'|'away'
 *
 * @property []Check $checks
 * @property []Subscriber $subscribers
 */
class Operator extends Model
{
    const STATUS_OFFLINE = 'offline';
    const STATUS_READY   = 'ready';
    const STATUS_AWAY    = 'away';

    protected static $_statusTitles = [
        self::STATUS_OFFLINE => 'Офлайн',
        self::STATUS_READY   => 'Готов',
        self::STATUS_AWAY    => 'Отошел'
    ];

    public static function statusTitles()
    {
        return self::$_statusTitles;
    }

    public function statusTitle()
    {
        return isset(self::$_statusTitles[$this->status])?self::$_statusTitles[$this->status]:'';
    }
}