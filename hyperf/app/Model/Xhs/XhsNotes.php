<?php


namespace App\Model\Xhs;


use App\Model\Merchant\Merchant;
use App\Model\Model;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Snowflake\Concern\Snowflake;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class XhsNotes extends Model
{
    use Snowflake;
    use SoftDeletes;

    protected $table = 'xhs_notes';

    protected $appends = [
      'publish_time_format'
    ];
    protected $fillable = [
        'note_id',
        'talent_id',
        'poster_id',
        'comments',
        'nickname' ,
        'title' ,
        'image' ,
        'likes',
        'publish_time',
        'views',
        'favorite',
    ];

    public function getPublishTimeFormatAttribute(){
            return $this->checkAttributes('publish_time',function($value){
                 return Date('Y-m-d H:i:s',$value);
            });
    }

    public function getIdAttribute($value){
        return (string)$value;
    }



}