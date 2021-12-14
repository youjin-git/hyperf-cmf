<?php


namespace Yj\Dependencies;


class LengthAwarePaginator extends \Hyperf\Paginator\LengthAwarePaginator
{
    public function toArray(): array
    {
        return [
            'page' => $this->currentPage(),
            'data' => $this->items->toArray(),
            'pageSize' => $this->perPage(),
            'total' => $this->total(),
        ];
    }
}