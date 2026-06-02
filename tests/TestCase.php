<?php

namespace CubeAgency\FilamentConstructor\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use CubeAgency\FilamentConstructor\FilamentConstructorServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\SupportServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            BladeIconsServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            LivewireServiceProvider::class,
            SupportServiceProvider::class,
            ActionsServiceProvider::class,
            SchemasServiceProvider::class,
            InfolistsServiceProvider::class,
            FormsServiceProvider::class,
            NotificationsServiceProvider::class,
            FilamentConstructorServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        config()->set('app.key', 'base64:j4TkRHy8hbJCJ255PmYRqn5pvxrhf3QKvJcrBj0M/gY=');
        config()->set('view.paths', array_merge(
            config('view.paths', []),
            [__DIR__ . '/Fixtures/views'],
        ));
    }
}
