<?php


namespace App\Model\System;


use App\Model\Merchant\Merchant;
use App\Model\Model;
use App\Model\Product\ProductContent;
use Hyperf\Database\Model\SoftDeletes;

class GroupData extends Model
{
    use SoftDeletes;

    protected $table = 'system_group_data';

    protected $fillable = [
        'group_id',
        'value',
        'sort',
        'status',
    ];

    public function group()
    {
        return $this->hasOne(Group::class, 'id', 'group_id');
    }

    public function getValueAttribute($value)
    {
        return $this->attributes['value'] = isJson($value) ? json_decode($value, true) : $value;
    }


    public function setValueAttribute($value)
    {
        return $this->attributes['value'] = is_array($value) ? json_encode($value) : $value;
    }

}