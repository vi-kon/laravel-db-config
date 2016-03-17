<?php

namespace ViKon\DbConfig\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Config
 *
 * @package ViKon\DbConfig\Model
 *
 * @author  Kovács Vince <vincekovacs@hotmail.com>
 *
 * @property integer $id
 * @property string  $namespace
 * @property string  $key
 * @property string  $type
 * @property string  $value
 * @property string  $default
 * @property integer $modified_by_user_id
 * @property string  $modified_at
 */
class Config extends Model
{
    const TABLE_NAME = 'config';

    const FIELD_ID                  = 'id';
    const FIELD_NAMESPACE           = 'namespace';
    const FIELD_KEY                 = 'key';
    const FIELD_TYPE                = 'type';
    const FIELD_VALUE               = 'value';
    const FIELD_DEFAULT             = 'default';
    const FIELD_MODIFIED_BY_USER_ID = 'modified_by_user_id';
    const FIELD_MODIFIED_AT         = 'modified_at';

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