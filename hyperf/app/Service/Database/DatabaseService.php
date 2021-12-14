<?php


namespace App\Service\Database;


use App\Model\Admin\Order;
use App\Service\BaseService;
use Hyperf\Config\Annotation\Value;
use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\Contract\PaginatorInterface;
use Hyperf\DbConnection\Db;
use Hyperf\Paginator\Paginator;
use Hyperf\Utils\Str;
use Yj\Tool\Page;
use function Swoole\Coroutine\Http\get;

class DatabaseService extends BaseService
{
    /**
     * @Value("databases.default.prefix")
     */
    protected $prefix;

    public function getTables()
    {
        $tables = Db::select('show tables');
        $tables = _Collect($tables);
        $tables = $tables->transform(function ($item) {
            $item = current($item);
            return $item;
        })->filter(function ($item) {
            return strpos($item, $this->prefix) === 0;
        })->transform(function ($item) {
            $item = Str::replaceFirst($this->prefix, "", $item);
            return ['tables_name' => $item];
        })->values();

        return make(Page::class)($tables);
    }


}