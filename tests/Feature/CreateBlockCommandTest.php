<?php

use Illuminate\Support\Facades\File;

afterEach(function () {
    File::deleteDirectory(app_path('Filament'));
});

it('creates a constructor block class from the stub', function () {
    $this->artisan('filament-constructor:create-block', ['name' => 'Hero'])
        ->assertSuccessful();

    $path = app_path('Filament/Constructor/Blocks/HeroBlock.php');

    expect(File::exists($path))->toBeTrue();

    expect(File::get($path))
        ->toContain('namespace App\Filament\Constructor\Blocks;')
        ->toContain('class HeroBlock extends BlockRenderer')
        ->toContain("return 'hero';");
});

it('namespaces nested blocks from a slash separated name', function () {
    $this->artisan('filament-constructor:create-block', ['name' => 'Layout/Hero'])
        ->assertSuccessful();

    $path = app_path('Filament/Constructor/Blocks/Layout/HeroBlock.php');

    expect(File::exists($path))->toBeTrue();

    expect(File::get($path))
        ->toContain('namespace App\Filament\Constructor\Blocks\Layout;')
        ->toContain('class HeroBlock extends BlockRenderer');
});
