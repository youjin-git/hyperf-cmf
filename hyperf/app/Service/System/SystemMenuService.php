<?php


namespace App\Service\System;


use App\Dao\System\SystemMenuDao;
use App\Service\BaseService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Collection;

class SystemMenuService extends BaseService
{

    /**
     * @Inject()
     * @var SystemMenuDao
     */
    public $systemMenuDao;

    public function list($params)
    {
        $data = $this->systemMenuDao->DaoWhere($params)->getList();
        $except = [];
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
        $systemMenuData->fill($params->toArray());
        return $systemMenuData->save();
    }

    public function add(Collection $params)
    {
        $this->systemMenuDao->check($id,$params);

        return $this->systemMenuDao->create($params->toArray());
    }

}