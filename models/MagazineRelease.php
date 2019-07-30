<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 27.07.19
 * Time: 16:52
 */

namespace models;

use classes\Model;

/**
 * This is the model class for table "magazine_release".
 *
 * The followings are the available columns in table 'magazine_release':
 * @property integer $id
 * @property integer $magazine_id
 * @property integer $number
 * @property string $release
 *
 * @property Magazine $magazine
 */
class MagazineRelease extends Model
{
    /**
     * @return Magazine|null
     */
    public function getMagazine()
    {
        return $this->belongTo(Magazine::class);
    }
}