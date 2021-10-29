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
        $except = [
            'title'
        ];
        $data->transform(function ($item) use ($except) {
            $item = collect($item);
            $item->offsetSet('meta', $item->only($except));
            return $item->except($except);
        });
        return $data;
    }

}