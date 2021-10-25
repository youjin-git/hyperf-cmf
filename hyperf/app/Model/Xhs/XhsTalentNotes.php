<?php


namespace App\Model\Xhs;


use App\Model\Merchant\Merchant;
use App\Model\Model;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Snowflake\Concern\Snowflake;

class XhsTalentNotes extends Model
{
    use Snowflake;
    use SoftDeletes;

    protected $table = 'xhs_talent_notes';

    protected $fillable = [
        'note_id',
        'likes',
        'time',
        'talent_id',
        'title'
    ];

    public function getIdAttribute($value){
        return (string)$value;
    }



}