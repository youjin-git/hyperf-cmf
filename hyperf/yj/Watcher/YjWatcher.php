<?php
namespace Yj\Watcher;

use Hyperf\Utils\Coroutine;
use Hyperf\Watcher\Driver\DriverInterface;
use Hyperf\Watcher\Option;
use Swoole\Coroutine\Channel;
use Yj\Watcher\Driver\ScanFileDriver;
use Symfony\Component\Console\Output\OutputInterface;
class YjWatcher
{

    /**
     * @var Option
     */
    protected $option;

    /**
     * @var ScanFileDriver
     */
    protected $driver;

    /**
     * @var OutputInterface
     */
    protected $output;


    public function __construct(Options $options, OutputInterface $output)
    {
        $this->option = $options;
        $this->driver = $this->getDriver();
        $this->output = $output;
        $this->channel = new Channel(1);
        $this->channel->push(1);
    }

    public function getDriver(){
        return make(ScanFileDriver::class,['option' => $this->option]);
    }

    public function run(){
        $this->reStart(true);
        $this->watchFile();

    }


    public function watchFile(){
        $channel = new Channel(1);
        Coroutine::create(function () use ($channel) {
            $this->driver->watch($channel);
        });
        while (true) {
            $pop = $channel->pop(0.001);
            if($pop){
                $this->output->writeln($pop);
                $this->reStart();
            }
        }
    }

    public function reStart(){

         //强杀
        Coroutine::create(function () {
            $pop = $this->channel->pop();
            dump($pop);

            $this->kill();
            $descriptorspec = [
                0 => STDIN,
                1 => STDOUT,
                2 => STDERR,
            ];
            if($pop==1){

//                 proc_open( "ps -ef | grep 'skeleton' | grep -v grep | awk '{print $2}' | xargs kill -9 2>&1", $descriptorspec, $pipes);
                $this->output->writeln('Start server ...');
                 proc_open("php ./bin/hyperf.php start", $descriptorspec, $pipes);
            }
            $this->output->writeln('Stop server success.');
            $this->channel->push($pop+1);
        });

    }

    public function kill()
    {
//        $file = $this->config->get('server.settings.pid_file');
//        if (empty($file)) {
//            throw new FileNotFoundException('The config of pid_file is not found.');
//        }
//        if (!$isStart && $this->filesystem->exists($file)) {
//            $pid = $this->filesystem->get($file);
//            try {
//
//                $this->output->writeln('Stop server...');
//                if (Process::kill((int)$pid, 0)) {
//                    Process::kill((int)$pid, SIGTERM);
//                }
//            } catch (\Throwable $exception) {
//                $this->output->writeln('Stop server failed. Please execute `composer dump-autoload -o`');
//            }
//        }
//        $this->output->write('111111111111');
        $this->forceKill();
    }

    function forceKill($match = '')
    {
        if (!$match) {
            $match = 'skeleton';
        }
        $command = "ps -ef | grep '$match' | grep -v grep | awk '{print $2}' | xargs kill -9 2>&1";
//        $this->output->write($command);
        // 找不到pid，强杀进程
        exec($command);
    }
}