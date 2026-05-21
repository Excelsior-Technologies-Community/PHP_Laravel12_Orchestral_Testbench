<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeTestbenchFeature extends Command
{
    protected $signature = 'make:testbench-feature {name}';
    protected $description = 'Create a new feature test class in tests/Feature';

    public function handle()
    {
        $name = $this->argument('name');
        $directory = base_path("tests/Feature");
        $path = "{$directory}/{$name}.php";

       
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

       
        if (File::exists($path)) {
            $this->error("File {$name}.php already exists!");
            return;
        }

        $stub = "<?php\n\nnamespace Tests\Feature;\n\nuse Tests\TestCase;\n\nclass {$name} extends TestCase\n{\n    public function test_example()\n    {\n        \$this->assertTrue(true);\n    }\n}";

        File::put($path, $stub);
        $this->info("Feature test [tests/Feature/{$name}.php] created successfully.");
    }
}