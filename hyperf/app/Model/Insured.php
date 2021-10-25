<?php
declare (strict_types=1);
namespace App\Model;

use App\Model\Model;
use Hyperf\Database\Model\Events\Saving;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Snowflake\Concern\Snowflake;

class Insured extends Model
{
    use Snowflake;
    use SoftDeletes;
    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;
    protected $table = 'insured';

    protected $fillable = [
        'name',
        'id_card',
        'household',
        'provice',
        'nation',
        'is_first_insured',
        'education',
        'uid',
        'id_card_positive_picture_id',
        'id_card_back_picture_id',
        'is_tax'
    ];

    protected $statusFormat = [
        0 => '关闭中',
        1 => '正常'
    ];

    protected $appends = ['create_time_format','picture_format','status_format','id_card_positive_picture','id_card_back_picture'];

    public function getCreateTimeFormatAttribute($value)
    {
        return  $this->getAttribute('create_time')->toDateTimeString();
    }


//    public function getCreateTimeAttribute($value)
//    {
//        return  $this->getAttribute('create_time')->toDateTimeString();
//    }

    public function getIdAttribute($value){
        return (string)$value;
    }

    public function getIdCardPositivePictureAttribute(){
        return  getFilePath($this->getAttribute('id_card_positive_picture_id'));
    }

    public function getIdCardBackPictureAttribute(){
        return  getFilePath($this->getAttribute('id_card_back_picture_id'));
    }


    public function getPictureFormatAttribute(){
        return  getFilePath($this->getAttribute('picture'));
    }

    public function getStatusFormatAttribute(){
        return $this->statusFormat[$this->getAttribute('status')];
    }
}
