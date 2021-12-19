<?php

namespace Yj\Generator;

//use Illuminate\Console\Concerns\CreatesMatchingTest;
//use Illuminate\Support\Str;
//use Symfony\Component\Console\Input\InputOption;
//use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Hyperf\Utils\Collection;
use Hyperf\Utils\Composer;
use Hyperf\Utils\Str;

#[Command]
class ModelMakeCommand extends GeneratorCommand
{
//    use CreatesMatchingTest;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'create:model';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Eloquent dao class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = '';

    protected $Suffix = '.php';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        parent::handle();
    }


    /**
     * Get the destination class path.
     *
     * @param string $name
     * @return string
     */
    protected function getPath($name, $extension = '.php')
    {
        foreach ($this->getAutoloadRules() as $prefix => $prefixPath) {
            if (strpos($name, $prefix) === 0) {
                return $prefixPath . str_replace('\\', '/', substr($name, strlen($prefix))) . $extension;
            }
        }
//        return str_replace('\\', '/', ($name)) . $extension;
    }


    protected function qualifyClass($name)
    {

        $rootNamespace = $this->rootNamespace();
        $className = $rootNamespace;
        $className = $name->transform(function ($item) {
                return Str::ucfirst($item);
            })->reduce(function ($classNameValue, $item) {
                return $classNameValue . '\\' . $item;
            }, $className) . $this->type;
        return $className;

    }


    protected function rootNamespace()
    {
        return 'App\Model';
    }


    protected function replaceClass($stub, $name)
    {

        $class = str_replace($this->getNamespace($name) . '\\', '', $name);

        return str_replace(['{{ class }}', '{{class}}'], $class, $stub);
    }

    /**
     * Create a model factory for the model.
     *
     * @return void
     */
    protected function createFactory()
    {
        $factory = Str::studly($this->argument('name'));

        $this->call('make:factory', [
            'name' => "{$factory}Factory",
            '--model' => $this->qualifyClass($this->getNameInput()),
        ]);
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createMigration()
    {
        $table = Str::snake(Str::pluralStudly(class_basename($this->argument('name'))));

        if ($this->option('pivot')) {
            $table = Str::singular($table);
        }

        $this->call('make:migration', [
            'name' => "create_{$table}_table",
            '--create' => $table,
        ]);
    }

    /**
     * Create a seeder file for the model.
     *
     * @return void
     */
    protected function createSeeder()
    {
        $seeder = Str::studly(class_basename($this->argument('name')));

        $this->call('make:seeder', [
            'name' => "{$seeder}Seeder",
        ]);
    }

    /**
     * Create a controller for the model.
     *
     * @return void
     */
    protected function createController()
    {
        $controller = Str::studly(class_basename($this->argument('name')));

        $modelName = $this->qualifyClass($this->getNameInput());

        $this->call('make:controller', array_filter([
            'name' => "{$controller}Controller",
            '--model' => $this->option('resource') || $this->option('api') ? $modelName : null,
            '--api' => $this->option('api'),
        ]));
    }

    public function buildClass($name)
    {
        $stub = $this->getFilesFactory()->read($this->getStub());
        return $this->replaceNamespace($stub, $name)
            ->replaceTable($stub, $name)
            ->replaceClass($stub, $name);
    }

    /**
     * Create a policy file for the model.
     *
     * @return void
     */
    protected function createPolicy()
    {
        $policy = Str::studly(class_basename($this->argument('name')));

        $this->call('make:policy', [
            'name' => "{$policy}Policy",
            '--model' => $this->qualifyClass($this->getNameInput()),
        ]);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return ('yj/Generator/stubs/model.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, ' / ')))
            ? $customPath
            : __DIR__ . $stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return is_dir(app_path('Models')) ? $rootNamespace . '\\Models' : $rootNamespace;
    }


}
