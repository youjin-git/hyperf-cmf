<?php

namespace App\Model\College;
use App\Model\Model;


class CollegeTask extends Model
{
    protected $table = 'college_task';

    protected $fillable = [
        'username',
        'gender',
        'mobile',
        'ticket',
        'subject_id',
        'score',
        'art_score',
        'comprehensive_ranking',
        'comprehensive_score',
        'ranking',
        'chinese_score',
        'foreign_score',
        'foreign_id',
        'qualified_subject_num',
        'school_test_score',
        'school_test_ranking',
        'school_test_name',
        'intention_school_id',
        'intention_major',
        'intention_city',
        'character',
        'interest',
    ];
}
