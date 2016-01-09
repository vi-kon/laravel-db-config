<?php

namespace ViKon\DbConfig\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Config
 *
 * @author  Kovács Vince <vincekovacs@hotmail.com>
 *
 * @package ViKon\DbConfig\Model
 *
 * @property integer $id
 * @property string  $key
 * @property string  $group
 * @property string  $type
 * @property string  $value
 * @property integer $modified_by
 * @property string  $modified_at
 * @method static \Illuminate\Database\Query\Builder|\ViKon\DbConfig\Model\Config whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\DbConfig\Model\Config whereKey($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\DbConfig\Model\Config whereGroup($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\DbConfig\Model\Config whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\DbConfig\Model\Config whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\DbConfig\Model\Config whereModifiedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\DbConfig\Model\Config whereModifiedAt($value)
 */
class Config extends Model
{
    /**
     *
     * Disable updated_at and created_at columns
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'config';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modifiedByUser()
    {
        return $this->belongsTo(config('db-config::table.users'), 'id', 'modified_by_user_id');
    }

    public function setValueAttribute($value)
    {
        switch ($this->type) {
            case 'int':
                $this->attributes['value'] = (string)$value;
                break;
            case 'bool':
                $this->attributes['value'] = (string)(int)$value;
                break;
            default:
                $this->attributes['value'] = (string)$value;
                break;
        }
    }

    public function getValueAttribute($value)
    {
        switch ($this->type) {
            case 'int':
                return (int)$value;
            case 'bool':
                return (bool)$value;
            default:
                return $value;
        }
    }
}