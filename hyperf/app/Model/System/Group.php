<?php


namespace App\Model\System;


use App\Model\Merchant\Merchant;
use App\Model\Model;
use App\Model\Product\ProductContent;
use Hyperf\Database\Model\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    protected $table = 'system_group';

    protected $fillable = [
        'name',
        'info',
        'key',
        'fields',
        'sort',
    ];

    public function getFieldsAttribute($value)
    {

        return $this->attributes['fields'] = isJson($value) ? json_decode($value, true) : $value;
    }
}