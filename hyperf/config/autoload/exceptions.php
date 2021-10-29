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

use App\Exception\Handler\AppExceptionHandler;

return [
    'handler' => [
        'http' => [
            \App\Exception\Handler\ApiDogExceptionHander::class,
            \App\Exception\Handler\YjValidationExceptionHandler::class,
            \App\Exception\Handler\YjExceptionHandler::class,
            \App\Exception\Handler\AuthorizeFailedExceptionHandler::class,
            Hyperf\HttpServer\Exception\Handler\HttpExceptionHandler::class,
            AppExceptionHandler::class,
        ],
    ],
];
