<?php


namespace App\Model\Product;


use App\Model\File;
use App\Model\Merchant\Merchant;
use App\Model\Model;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Snowflake\Concern\Snowflake;

class Category extends Model
{
    use SoftDeletes;
    protected $table = 'product_category';

    protected $fillable = [
      'cate_name',
        'path',
        'pid',
        'sort',
        'picture_id',
        'is_show',
        'level',
    ];

    public function getIdAttribute($value){
        return (string)$value;
    }



    public function picture(){
        return $this->hasOne(File::class,'id','picture_id');
    }

}