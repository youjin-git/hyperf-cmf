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

use App\Middleware\CorsMiddleware;
use Hyperf\Apidog\Middleware\ApiValidationMiddleware;

return [
    'http' => [
        CorsMiddleware::class,
//        Hyperf\Apidog\Middleware\ApiValidationMiddleware::class,
        \Hyperf\Validation\Middleware\ValidationMiddleware::class,

    ],
];
