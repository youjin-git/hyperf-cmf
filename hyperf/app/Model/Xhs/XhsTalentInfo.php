<?php


namespace App\Model\Xhs;


use App\Model\Merchant\Merchant;
use App\Model\Model;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Snowflake\Concern\Snowflake;

class XhsTalentInfo extends Model
{
    use Snowflake;
    use SoftDeletes;

    protected $table = 'xhs_talent_info';

    public $fillable = [
        'talent_id',
        'nickname',
    ];
    public function getIdAttribute($value){
        return (string)$value;
    }


}