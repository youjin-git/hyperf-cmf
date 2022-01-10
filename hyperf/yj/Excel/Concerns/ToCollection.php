<?php

namespace Yj\Excel\Concerns;

use Hyperf\Utils\Collection;

Interface ToCollection
{

    /**
     * @param Collection $collection
     * @return mixed
     */
    public function collection(Collection $collection);
}