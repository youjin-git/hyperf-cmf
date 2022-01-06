<?php

namespace App\Model\Generator;

use App\Model\Model;


class GeneratorTable extends Model
{
    protected $table = 'generator_table';
    protected $fillable = [
        'name'
    ];
}
