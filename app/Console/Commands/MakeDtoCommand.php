<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeDtoCommand extends Command
{
    protected $signature = 'make:dto {name}';
    protected $description = 'Create a new Data Transfer Object class';

    public function handle()
    {
        $name = $this->argument('name');

        // Normalize slashes for paths
        $name = str_replace('\\', '/', $name);

        // Extract class name and subdirectory
        $className = Str::afterLast($name, '/');
        $subPath   = Str::beforeLast($name, '/');
        $namespace = 'App\\DTOs' . ($subPath ? '\\' . str_replace('/', '\\', $subPath) : '');

        // Build the full file path
        $path = app_path("DTOs/{$name}.php");

        // Prevent overwriting
        if (File::exists($path)) {
            $this->error("DTO {$name} already exists!");
            return;
        }

        // Ensure directory exists
        $dir = dirname($path);
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        // Stub for DTO
        $stub = <<<PHP
        <?php

        namespace {$namespace};

        class {$className}
        {
            public function __construct(
                // define your properties here
            ) {}

            public static function fromRequest(\$request): self
            {
                return new self(
                    // map request fields here
                );
            }
        }
        PHP;

        // Save file
        File::put($path, $stub);

        $this->info("DTO {$className} created successfully at {$path}");
    }
}
