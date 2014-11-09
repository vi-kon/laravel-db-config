<?php

namespace ViKon\DbConfig\models;

use Illuminate\Database\Eloquent\Model;

/**
 * ViKon\DbConfig\models\Config
 *
 * @property integer $id
 * @property string  $key
 * @property string  $group
 * @property string  $type
 * @property string  $value
 * @property integer $modified_by
 * @property string  $modified_at
 * @method static \Illuminate\Database\Query\Builder|\ViKon\DbConfig\models\Config whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\DbConfig\models\Config whereKey($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\DbConfig\models\Config whereGroup($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\DbConfig\models\Config whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\DbConfig\models\Config whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\DbConfig\models\Config whereModifiedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\ViKon\DbConfig\models\Config whereModifiedAt($value)
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
        switch ($this->type)
        {
            case 'int':
                $this->attributes['value'] = (string) $value;
                break;
            case 'bool':
                $this->attributes['value'] = (string) (int) $value;
                break;
            default:
                $this->attributes['value'] = (string) $value;
                break;
        }
    }

    public function getValueAttribute($value)
    {
        switch ($this->type)
        {
            case 'int':
                return (int) $value;
            case 'bool':
                return (bool) $value;
            default:
                return $value;
        }
    }
}