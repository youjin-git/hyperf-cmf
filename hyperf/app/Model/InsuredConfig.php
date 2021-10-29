<?php
declare (strict_types=1);
namespace App\Model;

use App\Model\Model;
use Hyperf\Database\Model\Events\Saving;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Snowflake\Concern\Snowflake;

class InsuredConfig extends Model
{
    use Snowflake;
    use SoftDeletes;
    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;
    protected $table = 'insured_config';

    protected $fillable = [
        'month',
        'rate',
        'p_rate',
        'g_rate',
        'advance_payment',
        'p_pension_rate',
        'g_pension_rate',
        'p_medical_rate',
        'g_medical_rate',
        'p_unemployment_rate',
        'g_unemployment_rate',
        'p_injury_rate',
        'g_injury_rate',
        'p_birth_rate',
        'g_birth_rate',
        'p_disability_rate',
        'g_disability_rate',
        'social_security_min_base_price',
        'social_security_max_base_price',
        'fund_rate',
        'fund_min_base_price',
        'fund_max_base_price'
    ];

    public function get_total_price($start_month,$months){
            foreach($start_month as $v){

            }
    }



    protected $statusFormat = [
        0 => '关闭中',
        1 => '正常'
    ];




//    public function getCreateTimeAttribute($value)
//    {
//        return  $this->getAttribute('create_time')->toDateTimeString();
//    }

    public function getIdAttribute($value){
        return (string)$value;
    }

    
    public function getPictureFormatAttribute(){
        return  getFilePath($this->getAttribute('picture'));
    }

    public function getStatusFormatAttribute(){
        return $this->statusFormat[$this->getAttribute('status')];

    }
}
