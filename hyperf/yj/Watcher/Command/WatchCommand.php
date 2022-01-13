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
namespace Yj\Watcher\Command;

//use Hyperf\Watcher\Option;
//use Hyperf\Watcher\Watcher;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\InputOption;
use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Yj\Watcher\Options;
use Yj\Watcher\YjWatcher;

#[Command]
class WatchCommand extends HyperfCommand
{
    protected $container;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'yj:watch';



    public function handle()
    {

        $option = make(Options::class);

        $watcher = make(YjWatcher::class,['options'=>$option, 'output' => $this->output]);


//        $watcher = make(Watcher::class, [
//            'option' => $option,
//            'output' => $this->output,
//        ]);

        $watcher->run();
    }
}
