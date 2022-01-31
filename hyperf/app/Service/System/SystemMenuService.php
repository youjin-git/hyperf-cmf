<?php


namespace App\Service\System;


use App\Dao\System\SystemMenuDao;
use App\Service\BaseService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Collection;
use Hyperf\Utils\Str;

class SystemMenuService extends BaseService
{

    /**
     * @Inject()
     * @var SystemMenuDao
     */
    public $systemMenuDao;

    public function list($params)
    {
        $data = $this->systemMenuDao->DaoWhere($params)->orderBy('sort')->getList();
        $except = ['active','icon','params','type','title','hidden'];
        $data->transform(function ($item) use ($except) {
            $item = collect($item);
            $item->offsetSet('meta', $item->only($except));
            return $item->except($except);
        });
        return $data;
    }


    public function edit($id, Collection $params)
    {
        $this->systemMenuDao->check($id,$params);

        $systemMenuData = $this->systemMenuDao->DaoWhere([
            'id' => $id
        ])->firstOr(fn() => _Error('不存在数据'));

        $params->except('name');
        if($path = $params->get('path')){
            $params->offsetSet('name',implode(array_map('ucwords',explode('/',$path))));
        }

        $systemMenuData->fill($params->toArray());
        return $systemMenuData->save();
    }

    public function add(Collection $params)
    {
        $this->systemMenuDao->check(0,$params);
        return $this->systemMenuDao->create($params->toArray());
    }

    public function setPosition($menu_id, $target_menu_id, $types)
    {
        if(in_array($types,['after','before'])){
            $pid = $this->systemMenuDao->where('id',$target_menu_id)->value('pid');
            $this->systemMenuDao->where('id',$menu_id)->update(['pid'=>$pid]);
            $systemMenuDao = $this->systemMenuDao->where('pid',$pid)->orderBy('sort')->select([
                'id',
                'sort',
            ])->get()->transform(function ($item,$index)use($types,$target_menu_id){
                    $item->sort = $index*10;
                    return $item;
            });

            $sort = $systemMenuDao->where('id',$target_menu_id)->first()->sort;
         
            $systemMenuDao->transform(function ($item)use($menu_id,$types,$sort){
                       if($item['id'] == $menu_id){
                           if($types == 'after'){
                               $item['sort'] = $sort+1;
                           }
                           if($types == 'before'){
                               $item['sort'] = $sort-1;
                           }
                       }
                       return $item;
            })->sortBy(function ($item){
                return $item['sort'];
            })->values()->each(function($item,$index){
                $item->sort = $index*10;
                dump($item->id,$item->sort);
                return $item->save();
            });
        }

        if($types == 'inner'){
          $this->systemMenuDao->where('id',$menu_id)->update(['pid'=>$target_menu_id]);
        }
        return true;
    }

}