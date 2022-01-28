<?php

namespace App\Model\System;
use App\Model\Model;
use Hyperf\Database\Model\SoftDeletes;


class SystemFile extends Model
{
    use SoftDeletes;

    protected $table = 'system_file';

    protected $appends = [
      'full_path'
    ];

    protected $fillable = [
        'name',
        'path',
        'tags_id',
        'size',
        'suffix',
    ];

    public function getFullPathAttribute(){
        return $this->checkAttributes('path',function ($path){
            return $this->url($path);
        });
    }

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
