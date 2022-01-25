<?php

namespace App\Model\System;
use App\Model\Model;


class SystemFileTags extends Model
{
    protected $table = 'system_file_tags';

    protected $fillable = [
        'name',
    ];
}
