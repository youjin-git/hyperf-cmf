<?php

namespace App\Model\College;
use App\Model\Model;


class CollegeSchool extends Model
{
    protected $table = 'college_school';

    protected $fillable = [
        'code',
        'title',
    ];
}
