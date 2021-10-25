<?php


namespace App\Model\Xhs;


use App\Model\Merchant\Merchant;
use App\Model\Model;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Snowflake\Concern\Snowflake;

class XhsTalentFriend extends Model
{
    use Snowflake;
    use SoftDeletes;

    protected $table = 'xhs_talent_friend';

    public function getIdAttribute($value){
        return (string)$value;
    }

    protected $fillable = [
      'talent_id',
      'price1',
      'price2',
      'price3',
      'price4',
      'remark',
      'views',
    ];

    public function talent(){
        return $this->hasOne(XhsTalent::class,'id','talent_id');
    }

}