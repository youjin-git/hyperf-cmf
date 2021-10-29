<?php
declare (strict_types=1);
namespace App\Model;

use App\Model\Model;
use Hyperf\Database\Model\Events\Saving;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Snowflake\Concern\Snowflake;

class InsuredPriceValue extends Model
{
    public $timestamps = false;
    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;

    /**
     * @Inject()
     * @var User
     */
    protected $userModel;
    protected $table = 'insured_price_value';

    protected $fillable = [
        'month',
        'prices',
        'insured_price_config_id',
    ];

    public function getIdAttribute($value){
        return (string)$value;
    }

    public function insuredPriceConfig()
    {
        return $this->hasOne(InsuredPriceConfig::class,  'id','insured_price_config_id');
    }
    public function getPriceValue($userId,$month){
        $insured_price_config_id = $this->userModel->where('id',$userId)->value('insured_price_config_id');
        $prices = $this->where('insured_price_config_id',$insured_price_config_id)->where('month',$month)->value('prices');

        return $prices;
    }
    public function getPriceList($userId){
        $insured_price_config_id = $this->userModel->where('id',$userId)->value('insured_price_config_id');
        $lists =$this->where('insured_price_config_id',$insured_price_config_id)->pluck('prices','month');;
        return $lists;
    }
}
