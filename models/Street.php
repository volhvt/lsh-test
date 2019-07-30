<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 27.07.19
 * Time: 15:42
 */

namespace models;

use classes\Model;

/**
 * This is the model class for table "street".
 *
 * The followings are the available columns in table 'street':
 * @property integer $id
 * @property string $name
 * @property integer $country_id
 * @property integer $locality_id
 *
 * @property Country $country
 * @property Locality $locality
 */
class Street extends Model
{
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
}