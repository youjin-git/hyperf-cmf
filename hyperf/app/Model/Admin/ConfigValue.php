<?php
declare (strict_types=1);
namespace App\Model\Admin;

use App\Model\Model;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;

class ConfigValue extends Model
{
    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;
    protected $table = 'system_config_value';
    protected $fillable = [
            'key',
            'type',
            'value'
        ];

    protected $attributes = [
        'value'=>'""',
    ];
    public function more(array $keys,$analysis = false)
    {
        $configs = $this->whereIn('key',$keys)->select('key','value','type')->get()->toArray();

        $config = [];
        foreach($configs as $v){
              $config[$v['key']] = $analysis?$this->analysis($v):($v['value']??'');
        }
        return $config;
    }


    public function getValueAttribute($value)
    {
            return json_decode($value, true);
    }

    public function _get($key)
    {
            if(is_array($key)){
               return $this->more($key,true);
            }else{
               return $this->more([$key],true)[$key]??"";
            }

    }

    public function analysis($data){
            switch ($data['type']){
                case 'enumeration':
                    $val = explode("\n",trim($data['value']));
                    break;
                case 'value-label':
                    $val = [];
                    foreach(explode("\n",trim($data['value'])) as $v){
//                          dump($v);
                        [$value,$label] =  explode(':',$v);
                        $val[] = compact('label','value');
                    }
                    dump($value);
                    break;
                default:
                    $val = $data['value'];
                    break;
            }

            return $val??'';
    }

    public function _update($key){

    }
}
