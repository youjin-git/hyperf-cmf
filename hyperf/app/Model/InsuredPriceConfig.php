<?php
declare (strict_types=1);
namespace App\Model;

use App\Model\Model;
use Hyperf\Database\Model\Events\Saving;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Snowflake\Concern\Snowflake;

class InsuredPriceConfig extends Model
{
    public $timestamps = false;
    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;
    protected $table = 'insured_price_config';

    protected $fillable = [
        'name',
    ];

    public function getIdAttribute($value){
        return (string)$value;
    }

    

}
