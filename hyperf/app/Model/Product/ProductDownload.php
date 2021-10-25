<?php


namespace App\Model\Product;


use App\Model\Merchant\Merchant;
use App\Model\Model;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Snowflake\Concern\Snowflake;

class ProductDownload extends Model
{
    use SoftDeletes;

    protected $table = 'store_product_download';

    protected $fillable = [
       'product_id',
       'url',
    ];

    public function getIdAttribute($value){
        return (string)$value;
    }

    public function merchant()
    {
        return $this->hasOne(Merchant::class,'id','mer_id');
    }

    public function productContent(){
        return $this->hasOne(ProductContent::class,'product_id','id');
    }


}