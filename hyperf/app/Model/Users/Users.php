<?php

namespace App\Model\Users;
use App\Model\Model;


class Users extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'mobile',
        'openid',
    ];
}
