<?php
/**
 * Created by PhpStorm.
 * User: volhv
 * Date: 27.07.19
 * Time: 15:31
 */

namespace models;

use classes\Model;

/**
 * This is the model class for table "country".
 *
 * The followings are the available columns in table 'country':
 * @property integer $id
 * @property string $name
 * @property string $tid
 * @property string $tid2
 * @property string $iso
 *
 * @property Locality[] $localities
 * @property Street[] $streets
 */
class Country extends Model
{

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