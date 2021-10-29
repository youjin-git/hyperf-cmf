<?php
declare (strict_types=1);
namespace App\Model;

use App\Model\Model;
use Hyperf\Database\Model\Events\Saving;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;
use Hyperf\Database\Model\SoftDeletes;

class File extends Model
{
    use SoftDeletes;
    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;
    protected $table = 'system_file';
    protected $primaryKey= 'id';

    protected $fillable = [
        'path',
        'size',
        'name'
    ];

    protected $appends = [
        'full_path'
    ];

    public function getFullPathAttribute(){
        if(array_key_exists('path',$this->attributes)){
            return systemConfig('site_url').$this->attributes['path'];
        }else{
            return '';
        }
    }

    public function getFullPath($id)
    {

        if(is_array($id)){
            $data = $this->whereIn('id',$id)->pluck('path')->toArray();
            $data = array_map(function($item){
               return  systemConfig('site_url').$item;
            },$data);
            return $data;
        }else{
            return $id?systemConfig('site_url').$this->where('id',$id)->value('path'):'';
        }
    }






}
