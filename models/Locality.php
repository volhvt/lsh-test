<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 27.07.19
 * Time: 15:38
 */

namespace models;

use classes\Model;

/**
 * This is the model class for table "locality".
 *
 * The followings are the available columns in table 'locality':
 * @property integer $id
 * @property integer $country_id
 * @property string $name
 *
 * @property Country $country
 * @property Street[] $streets
 */
class Locality extends Model
{
    /**
     * @return Country|null
     */
    public function getCountry()
    {
        return $this->belongTo(Country::class);
    }

    /**
     * @return Street[]|null
     */
    public function getStreets()
    {
        return $this->hasMany(Street::class);
    }

    /**
     * @return bool
     */
    public function validate()
    {
        if (empty($this->name)) {
            $this->error('Укажите название');
        }

        if (empty($this->country_id)) {
            $this->error('Укажите страну');
        }

        return !$this->hasErrors();
    }
}