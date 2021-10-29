<?php


namespace App\Model\User;

use App\Model\Model;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Snowflake\Concern\Snowflake;

class UserSign extends Model
{

    protected $table = 'user_sign';

    protected $fillable = [
        'title',
        'user_id',
        'number',
        'integral',
        'day',
        'sign_num',
    ];

}