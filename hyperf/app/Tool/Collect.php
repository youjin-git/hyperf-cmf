<?php


namespace App\Tool;


use Hyperf\Utils\Collection;
use phpDocumentor\Reflection\Types\Mixed_;

class Collect extends Collection
{
    public function empty($callback = null)
    {
        $isEmpty = empty($this->items);
        if ($callback) {
            return $isEmpty ? $callback(clone $this) : $this;
        } else {
            return empty($this->items);
        }
    }
}