<?php
declare (strict_types=1);
namespace App\Model;

use App\Model\Model;
use Hyperf\Database\Model\Events\Saving;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;
use Hyperf\Database\Model\SoftDeletes;

class Waste extends Model
{
    use SoftDeletes;

    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;
    protected $table = 'waste';
    protected $primaryKey= 'id';

    protected $fillable = [
        'name',//废物名称
        'code',//废物编码
        'lit_code', //小代码
        'nums', //产生量/年
        'type', //物理形态
        'character_smell', //物理特性-气味
        'character_volatility', //物理特性-挥发性
        'character_viscosity',//物理特性-粘度
        'character_layered', //物理特性-分层
        'harmful_ingredients', //有害成分
        'other_harmful_ingredients', //其他有害成分
        'danger_character', //危险特性
        'radioactivity', //放射性物质
        'packing_require',//包装要求
        'packing', //包装方式
        'other_packing', //其它包装方式
        'component', //主要化学成分
        'technology',//废物产生工艺
        'picture',//图片id
        'user_id',//用户id
    ];

    protected $appends = ['checked','num'];

    public function getCheckedAttribute($value)
    {
        return false;
    }

    public function getNumAttribute($value)
    {
        return 1;
    }

}
