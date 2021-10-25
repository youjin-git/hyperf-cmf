<?php


namespace App\Model\Xhs;


use App\Model\Merchant\Merchant;
use App\Model\Model;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Database\Query\Builder;
use Hyperf\Snowflake\Concern\Snowflake;


class XhsTalent extends Model
{
    use Snowflake;
    use SoftDeletes;

    protected $table = 'xhs_talent';

    public function getIdAttribute($value){
        return (string)$value;
    }

    public $fillable = [
        'name',
        'nickname',
        'url',
        'wechat',
        'top',
        'email',
        'mechanism',
        'remark',
    ];

    public $appends = [
      'fans_format',
      'liked_format',
    ];

    public function friend(){
        return $this->hasOne(XhsTalentFriend::class,'talent_id','id');
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