<?php

namespace App\Service;

use App\Ide\ModelIDE;
use App\Model\Model;
use Hyperf\Database\Model\Builder;
use Hyperf\Utils\Context;

abstract class Service
{


    protected function error($msg)
    {
        Context::set('error_msg', $msg);
        return false;
    }

    public function MakeWhere($params)
    {
        return function (Builder $query) use ($params) {
            $this->make($query, $params);
        };
    }

    public function getError()
    {
        return Context::get('error_msg');
    }

    /**
     * @return Builder
     */
    public function where($params = [])
    {
        return $this->getModel = $this->getModel()->where($this->MakeWhere($params));
    }

    public function with()
    {

    }


}


