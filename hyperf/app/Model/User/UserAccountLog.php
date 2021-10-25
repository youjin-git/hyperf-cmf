<?php


namespace App\Model\User;

use App\Model\Model;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Snowflake\Concern\Snowflake;

class UserAccountLog extends Model
{
    use SoftDeletes;

    protected $table = 'user_account_log';

    protected $fillable = [
        'type',
        'user_id',
        'value',
        'account_type',
        'before_value',
        'after_value',
        'status',
        'remark',
    ];

}