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
 * This is the model class for table "magazine".
 *
 * The followings are the available columns in table 'magazine':
 * @property integer $id
 * @property string $title
 *
 * @property MagazineRelease[] $magazineReleases
 */
class Magazine extends Model
{
    /**
     * @return MagazineRelease[]
     * @throws \Exception
     */
    public function getMagazineReleases()
    {
        return $this->hasMany(MagazineRelease::class);
    }
}