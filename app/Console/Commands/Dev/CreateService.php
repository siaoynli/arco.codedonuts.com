<?php

namespace App\Console\Commands\Dev;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class CreateService extends GeneratorCommand
{
    protected static $defaultName = 'arco:create-service';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'arco:create-service';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service';


    protected $type = 'Service';


    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/service.stub');
    }


    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }


    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Services';
    }


    protected function buildClass($name)
    {
        $service = class_basename(Str::ucfirst(str_replace('Service', '', $name)));
        $namespace = $this->getNamespace($name);
        $replace = [
            '{{ serviceNamespace }}' => $namespace,
            '{{ service }}' => $service,
            '{{service}}' => $service,
        ];
        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }


}
