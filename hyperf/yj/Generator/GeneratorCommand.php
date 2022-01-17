<?php


namespace Yj\Generator;


use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Collection;
use Hyperf\Utils\Composer;
use Hyperf\Utils\Str;
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
        return $this->filesFactory->get('base_path');
    }

    public function handle()
    {

        $name = $this->qualifyClass($this->getNameInput());
    
        $path = $this->getPath($name);

        if ($this->alreadyExists()) {

        }
        $this->makeDir($path);
        dump(111);
        $stub = $this->getFilesFactory()->put($path, $this->buildClass($name));

        dd($stub, $path);
//        $this->files->put($path,$this->sortI)
        dd($path);

        // TODO: Implement handle() method.
    }

    public function buildClass($name)
    {
        $stub = $this->getFilesFactory()->read($this->getStub());
        return $this->replaceNamespace($stub, $name)
            ->replaceTable($stub, $name)
            ->replaceClass($stub, $name);
    }

    public function replaceTable(&$stub, $name)
    {
        $table = Str::snake($this->getNameInput()->last());

        $searches = [
            ['{{ table }}'],
            ['{{table}}'],
        ];

        foreach ($searches as $search) {
            $stub = str_replace(
                $search,
                [$table],
                $stub
            );
        }

        return $this;

    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param string $stub
     * @param string $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    {
        $searches = [
            ['DummyNamespace', 'DummyRootNamespace'],
            ['{{ namespace }}', '{{ rootNamespace }}'],
            ['{{namespace}}', '{{rootNamespace}}'],
        ];

        foreach ($searches as $search) {
            $stub = str_replace(
                $search,
                [$this->getNamespace($name), $this->rootNamespace()],
                $stub
            );
        }

        return $this;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @param string $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $class = str_replace($this->getNamespace($name) . '\\', '', $name);

        return str_replace(['DummyClass', '{{ class }}', '{{class}}'], $class, $stub);
    }


    /**
     * Get the full namespace for a given class, without the class name.
     *
     * @param string $name
     * @return string
     */
    protected function getNamespace($name)
    {
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
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
     * @return Collection
     */
    protected function getNameInput()
    {
        $name = ltrim(trim($this->argument('name')), '\\/');
        $name = str_replace('/', '\\', $name);
        $name = _Collect(explode('\\', $name));
        return $name;
    }

    /**
     * 生成代码
     */
    protected function create()
    {
        $this->call($this->name);
    }


    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
        ];
    }

}