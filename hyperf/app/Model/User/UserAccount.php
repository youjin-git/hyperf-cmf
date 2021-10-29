<?php


namespace App\Model\User;

use App\Model\Model;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Snowflake\Concern\Snowflake;

class UserAccount extends Model
{
    use SoftDeletes;

    protected $table = 'user_account';

    protected $fillable = [
        'type',
        'user_id',
        'value',
        'status',
    ];

}