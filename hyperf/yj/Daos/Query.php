<?php

namespace Yj\Daos;

trait Query
{

    public $daoQuerys = null;

    /**
     * @return array
     */
    public function getDaoQuerys(): array|null
    {
        return $this->daoQuerys;
    }

    /**
     * @param array $daoQuerys
     */
    public function setDaoQuerys($daoQuerys): void
    {
        $this->daoQuerys[] = $daoQuerys;
    }

}