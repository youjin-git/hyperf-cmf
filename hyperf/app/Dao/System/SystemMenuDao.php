<?php


namespace App\Dao\System;


use App\Dao\BaseDao;
use App\Model\System\SystemMenu;
use Hyperf\Database\Model\Builder;
use Hyperf\Utils\Collection;

/**
 * @var SystemMenu
 */
class SystemMenuDao extends \Yj\Daos\BaseDao
{
    public function check($id, Collection $params)
    {

        if ($path = $params->get('path')) {
            if ($this->where('id', '<>', $id)
                ->where('path', $params->get('path'))
                ->exists()) {
                _Error('path is exists');
            }
        }
    }
}