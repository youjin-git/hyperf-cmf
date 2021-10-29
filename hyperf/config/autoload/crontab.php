<?php
// config/autoload/crontab.php
use Hyperf\Crontab\Crontab;
return [
    'enable' => true,
    // 通过配置文件定义的定时任务
//    'crontab' => [
//        // Callback类型定时任务（默认）
//        (new Crontab())->setName('Foo')->setRule('* * * * *')->setCallback([App\Task\FooTask::class, 'execute'])->setMemo('这是一个示例的定时任务'),
//        // Command类型定时任务
//        (new Crontab())->setType('command')->setName('Bar')->setRule('* * * * *')->setCallback([
//            'command' => 'swiftmailer:spool:send',
//            // (optional) arguments
//            'fooArgument' => 'barValue',
//            // (optional) options
//            '--message-limit' => 1,
//        ]),
//    ],
];