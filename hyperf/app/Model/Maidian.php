<?php
declare (strict_types=1);

namespace App\Model;

use App\Model\Model;
use Hyperf\Database\Model\Events\Saving;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Snowflake\Concern\Snowflake;


class Maidian extends Model
{
    protected $table = 'maidian';

    protected $fillable = [
        'name',
        'type',
    ];

    protected $type_formats = [
        0=>'中介卖家',
        1=>'设计装修'
    ];

    protected $appends = [
      'type_format'
    ];

    public function getTypeFormatAttribute()
    {
        return $this->checkAttributes('type',function ($type){
            return $this->type_formats[$type];
        });
    }

}
