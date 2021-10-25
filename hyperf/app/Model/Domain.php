<?php
declare (strict_types=1);

namespace App\Model;

use App\Model\Model;
use Hyperf\Database\Model\Events\Saving;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Snowflake\Concern\Snowflake;

class Domain extends Model
{
    protected $table = 'domain';

    protected $fillable = [
        'id',
        'domain',
        'domain_title',
        'times'
    ];


}
