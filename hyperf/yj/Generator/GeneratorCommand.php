<?php


namespace Yj\Generator;


use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Composer;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Yj\Generator\Concerns\interactsWithIO;


class GeneratorCommand extends HyperfCommand
{

    use interactsWithIO;

    /**
     * @Inject()
     * @var \Hyperf\Filesystem\FilesystemFactory
     */
    protected $filesFactory;

    protected $name;

    public function getFilesFactory()
    {
        return $this->filesFactory->get('yj');
    }

    public function handle()
    {


        $name = $this->qualifyClass($this->getNameInput());
        $path = $this->getPath($name);
        if ($this->alreadyExists()) {

        }
        $this->makeDir($path);
        $stub = $this->getFilesFactory()->put($path, $this->buildClass($name));

        dd($stub);
//        $this->files->put($path,$this->sortI)
        dd($path);

        // TODO: Implement handle() method.
    }

    public function buildClass($name)
    {
        $stub = $this->getFilesFactory()->read($this->getStub());
//        dd($stub);
        return $this->replaceNamespace($stub,$name);
//        dd($stub);

    }

    public function makeDir($path)
    {
        if (is_dir(dirname($path))) {
            $this->getFilesFactory()->createDir(dirname($path));
        }
    }


    protected function alreadyExists()
    {
        return $this->getFilesFactory()->has($this->getPath($this->qualifyClass($this->getNameInput())));
    }

    protected function getAutoloadRules(): array
    {
        return data_get(Composer::getJsonContent(), 'autoload.psr-4', []);
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return trim($this->argument('name'));
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
        ];
    }

}