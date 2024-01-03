<?php

namespace CubeAgency\FilamentConstructor\Commands;

use Filament\Support\Commands\Concerns\CanManipulateFiles;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class FilamentConstructorCommand extends Command
{
    use CanManipulateFiles;

    public $signature = 'filament-constructor:create-block {name?} {--F|force}';

    public $description = 'Create Filament constructor block';

    public function handle(): int
    {
        $name = $this->argument('name') ?? $this->ask('Name (e.g. `Custom`)', 'name');

        $block = (string)Str::of($name)
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->replace('/', '\\')
            ->append('Block');
        $blockClass = (string)Str::of($block)->afterLast('\\');
        $blockNamespace = Str::of($block)->contains('\\') ?
            (string)Str::of($block)->beforeLast('\\') :
            '';

        $path = app_path(
            (string)Str::of($block)
                ->prepend('Filament\\Constructor\\Blocks\\')
                ->replace('\\', '/')
                ->append('.php'),
        );

        if (!$this->option('force') && $this->checkForCollision([
                $path,
            ])) {
            return static::INVALID;
        }

        $this->copyStubToApp('ConstructorBlock', $path, [
            'block_name' => Str::kebab($name),
            'class' => $blockClass,
            'translation' => $name,
            'namespace' => 'App\\Filament\\Constructor\\Blocks' . ($blockNamespace !== '' ? "\\{$blockNamespace}" : ''),
        ]);

        $this->info("Successfully created {$block} template");

        return static::SUCCESS;
    }

    protected function copyStubToApp(string $stub, string $targetPath, array $replacements = []): void
    {
        $filesystem = app(Filesystem::class);

        if (!$this->fileExists($stubPath = base_path("stubs/{$stub}.stub"))) {
            $stubPath = __DIR__ . "/../../stubs/{$stub}.stub";
        }

        $stub = Str::of($filesystem->get($stubPath));

        foreach ($replacements as $key => $replacement) {
            $stub = $stub->replace("{{ {$key} }}", $replacement);
        }

        $stub = (string)$stub;

        $this->writeFile($targetPath, $stub);
    }
}
