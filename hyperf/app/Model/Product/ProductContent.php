<?php


namespace App\Model\Product;


use App\Model\Merchant\Merchant;
use App\Model\Model;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Snowflake\Concern\Snowflake;

class ProductContent extends Model
{

    protected $table = 'store_product_content';

    protected $fillable = [
      'product_id',
      'content',
    ];

    public function getIdAttribute($value){
        return (string)$value;
    }




}