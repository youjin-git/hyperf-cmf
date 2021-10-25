<?php
declare (strict_types=1);

namespace App\Model\Admin;

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
    protected $primaryKey = 'id';

    protected $fillable = [
        'path',
        'size',
        'name'
    ];


    public function getFullPath($id)
    {

        if (is_array($id)) {
            $data = $this->whereIn('id', $id)->pluck('path')->toArray();
            $data = array_map(function ($item) {
                return $this->url($item);
            }, $data);
            return $data;
        } else {
            return $id ? $this->url($this->where('id', $id)->value('path')) : '';
        }
    }

    public function url($path)
    {
        return $path ? systemConfig('site_url') . $path : '';
    }


}
