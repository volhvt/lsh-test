<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 27.07.19
 * Time: 15:43
 */

namespace models;

use classes\Model;

/**
 * This is the model class for table "subscriber".
 *
 * The followings are the available columns in table 'subscriber':
 * @property integer $id
 * @property string $surname
 * @property string $name
 * @property string $patronymic
 * @property integer $country_id
 * @property integer $locality_id
 * @property integer $street_id
 * @property string $house_number
 * @property string $apartment_number
 *
 * @property string $fullName
 * @property Country $country
 * @property Locality $locality
 * @property Street $street
 * @property string $address
 *
 * @property Subscription[] $subscriptions
 * @property Check[] $checks
 */
class Subscriber extends Model
{

    /**
     * @param string $format
     * @return string
     */
    public function getFullName($format = 'S F P')
    {
        $replacements = array(
            'S' => $this->surname,
            'F' => $this->name,
            'P' => $this->patronymic,
            '  ' => ' '
        );
        return trim(strtr($format, $replacements));
    }

    /**
     * @return Country|null
     */
    public function getCountry()
    {
        return $this->belongTo(Country::class);
    }

    /**
     * @return Locality|null
     */
    public function getLocality()
    {
        return $this->belongTo(Locality::class);
    }

    /**
     * @return Street|null
     */
    public function getStreet()
    {
        return $this->belongTo(Street::class);
    }

    /**
     * @return Subscription[]|null
     */
    public function getSubscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * @return string
     */
    public function getAddress($format = 'C, г. L, ул. S, дом H, F')
    {
        $replacements = array(
            'C' => $this->country->name,
            'L' => $this->locality->name,
            'S' => $this->street->name,
            'H' => $this->house_number,
            'F' => $this->apartment_number,
            '  ' => ' '
        );
        return trim(strtr($format, $replacements));
    }
}