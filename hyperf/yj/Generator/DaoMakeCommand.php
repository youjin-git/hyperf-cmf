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
class DaoMakeCommand extends GeneratorCommand
{
//    use CreatesMatchingTest;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'create:dao';

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
    protected $type = 'Daos';


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

    public function buildClass($name)
    {
        $stub = $this->getFilesFactory()->read($this->getStub());
        return $this->replaceNamespace($stub, $name)
            ->replaceClass($stub, $name);
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
    }


    protected function qualifyClass($name)
    {

        $rootNamespace = $this->rootNamespace();
        $className = $rootNamespace;
        $className = $name->transform(function ($item) {
                return Str::studly($item);
            })->reduce(function ($classNameValue, $item) {
                return $classNameValue . '\\' . $item;
            }, $className) . $this->type;
        return $className;

    }


    protected function rootNamespace()
    {
        return 'App\Dao';
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
        return ('yj/Generator/stubs/dao.stub');
    }


}
