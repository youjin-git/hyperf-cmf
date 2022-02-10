<?php

namespace App\Model\Order;
use App\Model\Maidian;
use App\Model\Model;
use App\Model\System\SystemFile;
use Hyperf\Database\Model\SoftDeletes;


class OrderMaidianDesc extends Model
{
    use SoftDeletes;
    protected $table = 'order_maidian_desc';

    protected $fillable = [
        'name',
        'maidian_id',
        'order_id',
        'order_maidian_id',
        'desc',
        'images',
    ];

    protected $appends = [
        'images_format'
    ];

    public function getImagesFormatAttribute(){
        return $this->checkAttributes('images',function ($images){
                return App(SystemFile::class)->whereIn('id',json_decode($images,true))->get();
        });
    }

    public function Maidian(){
        return $this->hasOne(Maidian::class,'id','maidian_id');
    }
}
