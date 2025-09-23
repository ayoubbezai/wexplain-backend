<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeServiceCommand extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Create a new Service class';

    public function handle()
    {
        $name = $this->argument('name');

        // Normalize slashes
        $name = str_replace('\\', '/', $name);

        // Extract class name and subdirectory
        $className = Str::afterLast($name, '/');
        $subPath   = Str::beforeLast($name, '/');
        $namespace = 'App\\Services' . ($subPath ? '\\' . str_replace('/', '\\', $subPath) : '');
        $path      = app_path("Services/{$name}.php");

        // Prevent overwrite
        if (File::exists($path)) {
            $this->error("Service {$name} already exists!");
            return;
        }

        // Ensure directory exists
        $dir = dirname($path);
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        // Stub content
        $stub = <<<PHP
<?php

namespace {$namespace};

class {$className}
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    // Add your service methods here
}
PHP;

        File::put($path, $stub);

        $this->info("Service {$className} created successfully at {$path}");
    }
}
