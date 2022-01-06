<?php

declare(strict_types=1);

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use Hyperf\Contract\LengthAwarePaginatorInterface;
use Yj\Dependencies\LengthAwarePaginator;

return [
    \Hyperf\HttpServer\Router\DispatcherFactory::class => \Yj\Apidog\DispatcherFactory::class,
    LengthAwarePaginatorInterface::class => LengthAwarePaginator::class,
];
