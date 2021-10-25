<?php


namespace App\Model\Xhs;


use App\Model\Admin\Admin;
use App\Model\Merchant\Merchant;
use App\Model\Model;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Snowflake\Concern\Snowflake;

class XhsReport extends Model
{
    use Snowflake;
    use SoftDeletes;

    protected $table = 'xhs_report';


    public function getIdAttribute($value){
        return (string)$value;
    }
    
    public function getTalentIdAttribute($value){
        return (string)$value;
    }

    public $appends = [
        'fans_format',
        'liked_format',
    ];

    protected $fillable = [
        'image',
        'liked',
        'location',
        'report_price',
        'nickname',
        'fans',
        'follows',
        'gender',
        'desc',
        'notes',
        'price',
        'views',
        'num',
        'rate',
        'types',
        'talent_id',
        'record_id',
        'record_admin_id',
    ];

    public function XhsReport(){
        return $this->hasMany(XhsReport::class,'record_id','record_id');
    }

    public function admin(){
        return $this->hasOne(Admin::class,'id','record_admin_id');
    }

    public function getFansFormatAttribute(){
        return $this->checkAttributes('fans',function ($value){
            if($value>10000){
                $value = round($value/10000,2).'万';
            }
            return $value;
        });
    }

    public function getLikedFormatAttribute(){
        return $this->checkAttributes('liked',function ($value){
            if($value>10000){
                $value = round($value/10000,2).'万';
            }
            return $value;
        });
    }
}