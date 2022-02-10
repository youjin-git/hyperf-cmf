<?php

namespace App\Model\Order;
use App\Model\Maidian;
use App\Model\Model;
use App\Model\System\SystemFile;

class OrderChange extends Model
{
    protected $table = 'order_change';

    protected $fillable = [
        'type',
        'order_id',
        'content',
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
}
