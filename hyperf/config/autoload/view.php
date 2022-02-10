<?php
declare(strict_types=1);

use Hyperf\View\Mode;
use Hyperf\View\Engine\ThinkEngine;

return [
    // 使用的渲染引擎
    'engine' => ThinkEngine::class,
    // 不填写则默认为 Task 模式，推荐使用 Task 模式
    'mode' => Mode::TASK,
    'config' => [
        // 若下列文件夹不存在请自行创建
        'view_path' => BASE_PATH . '/public/',
        'cache_path' => BASE_PATH . '/runtime/view/',
        'tpl_replace_string'  =>  [
            '__STATIC__'=>'/static',
            '__JS__' => '/static/javascript',
        ]
    ],
];